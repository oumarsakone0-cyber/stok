<?php
// =====================================================
// API PRODUITS - Aligne sur api_gestion_bancaire.php
// Tables : app_produits, app_produits_categories, app_produits_sous_categories
// =====================================================

$allowed_origins = [
    'http://localhost:5173','http://localhost:5174','http://localhost:5175',
    'http://127.0.0.1:5173','http://127.0.0.1:5174','http://127.0.0.1:5175'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
    exit;
}

ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// ===================== PHPMailer + JWT =====================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require_once 'Mailer.php';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/libs/JWT.php';
require_once __DIR__ . '/libs/Key.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('JWT_SECRET', '4e9f1c7b2a6d8f3c5e9a0b7d4c2f8a6e1b3c9d7e5f0a2b4c6d8e1f3a5b7c9d2');

// ===================== Fonctions utilitaires =====================
function jsonResponse($code, $payload) {
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function readJsonBody() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        jsonResponse(400, ['success' => false, 'error' => 'JSON invalide']);
    }
    return $data ?: [];
}

function getInt($value, $default = null) {
    if ($value === null || $value === '') return $default;
    return (int)$value;
}

function getStr($value, $default = '') {
    if ($value === null) return $default;
    return trim((string)$value);
}

function authenticateJWT() {
    $headers = getallheaders();
    $headersLower = [];
    foreach ($headers as $k => $v) {
        $headersLower[strtolower($k)] = $v;
    }
    $authHeader = $headersLower['authorization'] ?? '';

    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        jsonResponse(401, ['success' => false, 'error' => 'Token requis']);
    }

    try {
        $decoded = JWT::decode($matches[1], new Key(JWT_SECRET, 'HS256'));
        return (array)$decoded->data;
    } catch (Throwable $e) {
        jsonResponse(401, ['success' => false, 'error' => 'Token invalide ou expire']);
    }
}

// ===================== Notification Email aux Admins =====================
function sendProduitEmailToAdmins($db, $id_entreprise, $subject, $htmlContent, $auteur = '') {
    try {
        $sql = "SELECT email, nom, prenom 
                FROM app_utilisateurs 
                WHERE statut = 'actif' AND id_role = 1";
        $params = [];
        if ($id_entreprise !== null) {
            $sql .= " AND (id_entreprise = ? OR id_entreprise IS NULL)";
            $params[] = $id_entreprise;
        }
        $admins = $db->query($sql, $params);
        if (empty($admins)) return;

        foreach ($admins as $admin) {
            $to = trim($admin['email'] ?? '');
            if ($to === '') continue;

            $nomComplet = trim(($admin['prenom'] ?? '') . ' ' . ($admin['nom'] ?? ''));
            $body  = "<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>";
            $body .= "<h2 style='color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;'>"
                   . htmlspecialchars($subject) . "</h2>";
            $body .= "<p>Bonjour " . htmlspecialchars($nomComplet ?: 'Admin') . ",</p>";
            if ($auteur !== '') {
                $body .= "<p>Action effectuee par <strong>" . htmlspecialchars($auteur) . "</strong>.</p>";
            }
            $body .= $htmlContent;
            $body .= "<p style='color:#666;font-size:12px;margin-top:20px;border-top:1px solid #eee;padding-top:10px;'>"
                   . "Notification automatique - Module Produits."
                   . "</p></div>";

            if (class_exists('Mailer') && method_exists('Mailer', 'sendSimpleHtml')) {
                Mailer::sendSimpleHtml($to, $subject, $body);
            } elseif (class_exists('Mailer') && method_exists('Mailer', 'sendCustom')) {
                Mailer::sendCustom($to, $subject, $body);
            }
        }
    } catch (Throwable $e) {
        error_log("sendProduitEmailToAdmins error: " . $e->getMessage());
    }
}

// ===================== AUTHENTIFICATION JWT =====================
$authUser = authenticateJWT();
$auth_user_id       = $authUser['id']            ?? null;
$auth_id_entreprise = $authUser['id_entreprise'] ?? null;
$auth_role          = $authUser['role']           ?? null;
$auth_nom           = trim(($authUser['prenom'] ?? '') . ' ' . ($authUser['nom'] ?? ''));

try {
    if (!class_exists('Database')) {
        throw new Exception("Classe Database introuvable dans config/database.php");
    }
    $db = new Database();

    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';

    // id_entreprise vient du token, pas des parametres
    $id_entreprise = $auth_id_entreprise;

    // ===================== GET =====================
    if ($method === 'GET') {

        // ---------- PRODUITS ----------

        if ($action === 'list') {
            $search          = getStr($_GET['search'] ?? '');
            $id_categorie    = getInt($_GET['id_categorie'] ?? null, null);
            $id_sous_categorie = getInt($_GET['id_sous_categorie'] ?? null, null);
            $statut          = getStr($_GET['statut'] ?? '');
            $id_fournisseur  = getInt($_GET['id_fournisseur'] ?? null, null);

            $sql = "SELECT 
                        p.id_produit as id,
                        p.libelle,
                        p.description,
                        p.sku,
                        p.reference,
                        p.code_barres,
                        p.qr_code,
                        p.prix_achat,
                        p.prix_vente,
                        p.image,
                        p.unite_mesure,
                        p.peremption,
                        p.statut,
                        p.marque,
                        p.entrepot,
                        p.quantite_init,
                        p.seuil_alerte,
                        p.id_categorie,
                        p.id_sous_categorie,
                        p.id_fournisseur,
                        p.create_by,
                        p.modif_user,
                        p.date_creation,
                        p.date_modification,
                        c.libelle as categorie_nom,
                        sc.libelle as sous_categorie_nom
                    FROM app_produits p
                    LEFT JOIN app_produits_categories c ON c.id_categorie = p.id_categorie
                    LEFT JOIN app_produits_sous_categories sc ON sc.id_sous_categorie = p.id_sous_categorie
                    WHERE p.id_entreprise = ?";
            $params = [$id_entreprise];

            if ($statut !== '' && in_array($statut, ['actif', 'inactif', 'archive'])) {
                $sql .= " AND p.statut = ?";
                $params[] = $statut;
            }
            if ($id_categorie !== null) {
                $sql .= " AND p.id_categorie = ?";
                $params[] = $id_categorie;
            }
            if ($id_sous_categorie !== null) {
                $sql .= " AND p.id_sous_categorie = ?";
                $params[] = $id_sous_categorie;
            }
            if ($id_fournisseur !== null) {
                $sql .= " AND p.id_fournisseur = ?";
                $params[] = $id_fournisseur;
            }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $sql .= " AND (p.libelle LIKE ? OR p.sku LIKE ? OR p.reference LIKE ? OR p.code_barres LIKE ? OR p.marque LIKE ?)";
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
            }

            $sql .= " ORDER BY p.date_creation DESC";
            $rows = $db->query($sql, $params);
            if (!is_array($rows)) $rows = [];
            foreach ($rows as &$r) {
                $r['prix_achat'] = (float)($r['prix_achat'] ?? 0);
                $r['prix_vente'] = (float)($r['prix_vente'] ?? 0);
                $r['quantite_init'] = (int)($r['quantite_init'] ?? 0);
                $r['seuil_alerte'] = (int)($r['seuil_alerte'] ?? 0);
            }
            unset($r);
            jsonResponse(200, ['success' => true, 'data' => $rows]);
        }

        if ($action === 'single') {
            $id = getInt($_GET['id'] ?? null, null);
            if ($id === null) jsonResponse(400, ['success' => false, 'error' => 'id manquant']);

            $row = $db->query(
                "SELECT p.*, c.libelle as categorie_nom, sc.libelle as sous_categorie_nom
                 FROM app_produits p
                 LEFT JOIN app_produits_categories c ON c.id_categorie = p.id_categorie
                 LEFT JOIN app_produits_sous_categories sc ON sc.id_sous_categorie = p.id_sous_categorie
                 WHERE p.id_produit = ? AND p.id_entreprise = ?",
                [$id, $id_entreprise]
            );
            jsonResponse(200, ['success' => true, 'data' => $row[0] ?? null]);
        }

        // ---------- CATEGORIES ----------

        if ($action === 'list_categories') {
            $rows = $db->query(
                "SELECT id_categorie as id, libelle, date_creation FROM app_produits_categories WHERE id_entreprise = ? ORDER BY libelle ASC",
                [$id_entreprise]
            );
            if (!is_array($rows)) $rows = [];
            jsonResponse(200, ['success' => true, 'data' => $rows]);
        }

        // ---------- SOUS-CATEGORIES ----------

        if ($action === 'list_sous_categories') {
            $id_cat = getInt($_GET['id_categorie'] ?? null, null);

            $sql = "SELECT sc.id_sous_categorie as id, sc.id_categorie, sc.libelle, sc.rayon, sc.palier, sc.zone, sc.date_creation,
                           c.libelle as categorie_nom
                    FROM app_produits_sous_categories sc
                    LEFT JOIN app_produits_categories c ON c.id_categorie = sc.id_categorie
                    WHERE sc.id_entreprise = ?";
            $params = [$id_entreprise];

            if ($id_cat !== null) {
                $sql .= " AND sc.id_categorie = ?";
                $params[] = $id_cat;
            }
            $sql .= " ORDER BY sc.libelle ASC";
            $rows = $db->query($sql, $params);
            if (!is_array($rows)) $rows = [];
            jsonResponse(200, ['success' => true, 'data' => $rows]);
        }

        // ---------- FOURNISSEURS ----------

        if ($action === 'list_fournisseurs') {
            $rows = $db->query(
                "SELECT id_fournisseur as id, nom, telephone, email FROM app_fournisseurs WHERE id_entreprise = ? ORDER BY nom ASC",
                [$id_entreprise]
            );
            if (!is_array($rows)) $rows = [];
            jsonResponse(200, ['success' => true, 'data' => $rows]);
        }

        jsonResponse(404, ['success' => false, 'error' => 'Action GET inconnue']);
    }

    // ===================== POST =====================
    if ($method === 'POST') {
        $body = readJsonBody();

        // ---------- PRODUITS CRUD ----------

        if ($action === 'add_produit') {
            $libelle = getStr($body['libelle'] ?? '');
            if ($libelle === '') jsonResponse(400, ['success' => false, 'error' => 'Libelle requis']);

            $sql = "INSERT INTO app_produits 
                    (id_entreprise, id_fournisseur, id_categorie, id_sous_categorie, libelle, description, 
                     prix_achat, prix_vente, image, code_barres, sku, qr_code, reference, unite_mesure, 
                     peremption, statut, marque, entrepot, quantite_init, seuil_alerte, create_by, date_creation)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $params = [
                $id_entreprise,
                getInt($body['id_fournisseur'] ?? null, null),
                getInt($body['id_categorie'] ?? null, null),
                getInt($body['id_sous_categorie'] ?? null, null),
                $libelle,
                getStr($body['description'] ?? ''),
                (float)($body['prix_achat'] ?? 0),
                (float)($body['prix_vente'] ?? 0),
                getStr($body['image'] ?? ''),
                getStr($body['code_barres'] ?? ''),
                getStr($body['sku'] ?? ''),
                getStr($body['qr_code'] ?? ''),
                getStr($body['reference'] ?? ''),
                getStr($body['unite_mesure'] ?? 'unite'),
                getStr($body['peremption'] ?? ''),
                getStr($body['statut'] ?? 'actif'),
                getStr($body['marque'] ?? ''),
                getStr($body['entrepot'] ?? ''),
                (int)($body['quantite_init'] ?? 0),
                (int)($body['seuil_alerte'] ?? 0),
                $auth_user_id
            ];
            $db->query($sql, $params);
            $new_id = (int)$db->lastInsertId();

            // Email notification
            $prix_fmt = number_format((float)($body['prix_vente'] ?? 0), 0, ',', ' ');
            $emailHtml = "
                <table style='width:100%;border-collapse:collapse;margin:15px 0;'>
                    <tr style='background:#f8f9fa;'><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Produit</td><td style='padding:10px;border:1px solid #dee2e6;'>" . htmlspecialchars($libelle) . "</td></tr>
                    <tr><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Prix vente</td><td style='padding:10px;border:1px solid #dee2e6;font-weight:bold;'>{$prix_fmt} FCFA</td></tr>
                    <tr style='background:#f8f9fa;'><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Quantite initiale</td><td style='padding:10px;border:1px solid #dee2e6;'>" . (int)($body['quantite_init'] ?? 0) . "</td></tr>
                </table>
            ";
            sendProduitEmailToAdmins($db, $id_entreprise, "Nouveau produit : {$libelle}", $emailHtml, $auth_nom);

            jsonResponse(200, ['success' => true, 'data' => ['id_produit' => $new_id]]);
        }

        if ($action === 'update_produit') {
            $id = getInt($body['id_produit'] ?? null, null);
            if ($id === null) jsonResponse(400, ['success' => false, 'error' => 'id_produit requis']);

            $libelle = getStr($body['libelle'] ?? '');
            if ($libelle === '') jsonResponse(400, ['success' => false, 'error' => 'Libelle requis']);

            $sql = "UPDATE app_produits SET 
                        id_fournisseur = ?, id_categorie = ?, id_sous_categorie = ?,
                        libelle = ?, description = ?, prix_achat = ?, prix_vente = ?,
                        image = ?, code_barres = ?, sku = ?, qr_code = ?, reference = ?,
                        unite_mesure = ?, peremption = ?, statut = ?, marque = ?,
                        entrepot = ?, quantite_init = ?, seuil_alerte = ?, modif_user = ?, date_modification = NOW()
                    WHERE id_produit = ? AND id_entreprise = ?";

            $params = [
                getInt($body['id_fournisseur'] ?? null, null),
                getInt($body['id_categorie'] ?? null, null),
                getInt($body['id_sous_categorie'] ?? null, null),
                $libelle,
                getStr($body['description'] ?? ''),
                (float)($body['prix_achat'] ?? 0),
                (float)($body['prix_vente'] ?? 0),
                getStr($body['image'] ?? ''),
                getStr($body['code_barres'] ?? ''),
                getStr($body['sku'] ?? ''),
                getStr($body['qr_code'] ?? ''),
                getStr($body['reference'] ?? ''),
                getStr($body['unite_mesure'] ?? 'unite'),
                getStr($body['peremption'] ?? ''),
                getStr($body['statut'] ?? 'actif'),
                getStr($body['marque'] ?? ''),
                getStr($body['entrepot'] ?? ''),
                (int)($body['quantite_init'] ?? 0),
                (int)($body['seuil_alerte'] ?? 0),
                $auth_user_id,
                $id,
                $id_entreprise
            ];
            $db->query($sql, $params);

            // Email notification
            $prix_fmt = number_format((float)($body['prix_vente'] ?? 0), 0, ',', ' ');
            $emailHtml = "
                <table style='width:100%;border-collapse:collapse;margin:15px 0;'>
                    <tr style='background:#f8f9fa;'><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Produit</td><td style='padding:10px;border:1px solid #dee2e6;'>" . htmlspecialchars($libelle) . "</td></tr>
                    <tr><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Prix vente</td><td style='padding:10px;border:1px solid #dee2e6;font-weight:bold;'>{$prix_fmt} FCFA</td></tr>
                </table>
            ";
            sendProduitEmailToAdmins($db, $id_entreprise, "Produit modifie : {$libelle}", $emailHtml, $auth_nom);

            jsonResponse(200, ['success' => true]);
        }

        if ($action === 'delete_produit') {
            $id = getInt($body['id_produit'] ?? null, null);
            if ($id === null) jsonResponse(400, ['success' => false, 'error' => 'id_produit requis']);

            // Recuperer infos avant archivage pour l'email
            $info = $db->query("SELECT libelle, prix_vente FROM app_produits WHERE id_produit = ? AND id_entreprise = ?", [$id, $id_entreprise]);

            // Soft delete : on archive au lieu de supprimer
            $db->query(
                "UPDATE app_produits SET statut = 'archive', modif_user = ?, date_modification = NOW() WHERE id_produit = ? AND id_entreprise = ?",
                [$auth_user_id, $id, $id_entreprise]
            );

            if (!empty($info)) {
                $p = $info[0];
                $emailHtml = "
                    <p style='color:#dc3545;font-weight:bold;'>Produit archive</p>
                    <table style='width:100%;border-collapse:collapse;margin:15px 0;'>
                        <tr style='background:#f8f9fa;'><td style='padding:10px;font-weight:bold;border:1px solid #dee2e6;'>Produit</td><td style='padding:10px;border:1px solid #dee2e6;'>" . htmlspecialchars($p['libelle']) . "</td></tr>
                    </table>
                ";
                sendProduitEmailToAdmins($db, $id_entreprise, "Produit archive : " . $p['libelle'], $emailHtml, $auth_nom);
            }

            jsonResponse(200, ['success' => true]);
        }

        // ---------- CATEGORIES CRUD ----------

        if ($action === 'add_categorie') {
            $libelle = getStr($body['libelle'] ?? '');
            if ($libelle === '') jsonResponse(400, ['success' => false, 'error' => 'Libelle requis']);

            $db->query(
                "INSERT INTO app_produits_categories (id_entreprise, libelle, date_creation) VALUES (?, ?, NOW())",
                [$id_entreprise, $libelle]
            );
            $new_id = (int)$db->lastInsertId();
            jsonResponse(200, ['success' => true, 'data' => ['id_categorie' => $new_id]]);
        }

        if ($action === 'update_categorie') {
            $id = getInt($body['id_categorie'] ?? null, null);
            $libelle = getStr($body['libelle'] ?? '');
            if ($id === null || $libelle === '') jsonResponse(400, ['success' => false, 'error' => 'id_categorie et libelle requis']);

            $db->query(
                "UPDATE app_produits_categories SET libelle = ? WHERE id_categorie = ? AND id_entreprise = ?",
                [$libelle, $id, $id_entreprise]
            );
            jsonResponse(200, ['success' => true]);
        }

        if ($action === 'delete_categorie') {
            $id = getInt($body['id_categorie'] ?? null, null);
            if ($id === null) jsonResponse(400, ['success' => false, 'error' => 'id_categorie requis']);

            // Verifier s'il y a des produits dans cette categorie
            $check = $db->query("SELECT COUNT(*) as c FROM app_produits WHERE id_categorie = ? AND id_entreprise = ?", [$id, $id_entreprise]);
            $count = !empty($check) ? (int)($check[0]['c'] ?? 0) : 0;
            if ($count > 0) {
                jsonResponse(400, ['success' => false, 'error' => "Impossible de supprimer : {$count} produit(s) dans cette categorie"]);
            }

            $db->query("DELETE FROM app_produits_categories WHERE id_categorie = ? AND id_entreprise = ?", [$id, $id_entreprise]);
            jsonResponse(200, ['success' => true]);
        }

        // ---------- SOUS-CATEGORIES CRUD ----------

        if ($action === 'add_sous_categorie') {
            $libelle    = getStr($body['libelle'] ?? '');
            $id_cat     = getInt($body['id_categorie'] ?? null, null);
            if ($libelle === '' || $id_cat === null) jsonResponse(400, ['success' => false, 'error' => 'libelle et id_categorie requis']);

            $rayon  = getStr($body['rayon'] ?? '');
            $palier = getStr($body['palier'] ?? '');
            $zone   = getStr($body['zone'] ?? '');

            $db->query(
                "INSERT INTO app_produits_sous_categories (id_entreprise, id_categorie, libelle, rayon, palier, zone, date_creation) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [$id_entreprise, $id_cat, $libelle, $rayon, $palier, $zone]
            );
            $new_id = (int)$db->lastInsertId();
            jsonResponse(200, ['success' => true, 'data' => ['id_sous_categorie' => $new_id]]);
        }

        if ($action === 'update_sous_categorie') {
            $id      = getInt($body['id_sous_categorie'] ?? null, null);
            $libelle = getStr($body['libelle'] ?? '');
            $id_cat  = getInt($body['id_categorie'] ?? null, null);
            if ($id === null || $libelle === '' || $id_cat === null) jsonResponse(400, ['success' => false, 'error' => 'id_sous_categorie, libelle et id_categorie requis']);

            $rayon  = getStr($body['rayon'] ?? '');
            $palier = getStr($body['palier'] ?? '');
            $zone   = getStr($body['zone'] ?? '');

            $db->query(
                "UPDATE app_produits_sous_categories SET id_categorie = ?, libelle = ?, rayon = ?, palier = ?, zone = ? WHERE id_sous_categorie = ? AND id_entreprise = ?",
                [$id_cat, $libelle, $rayon, $palier, $zone, $id, $id_entreprise]
            );
            jsonResponse(200, ['success' => true]);
        }

        if ($action === 'delete_sous_categorie') {
            $id = getInt($body['id_sous_categorie'] ?? null, null);
            if ($id === null) jsonResponse(400, ['success' => false, 'error' => 'id_sous_categorie requis']);

            // Verifier s'il y a des produits dans cette sous-categorie
            $check = $db->query("SELECT COUNT(*) as c FROM app_produits WHERE id_sous_categorie = ? AND id_entreprise = ?", [$id, $id_entreprise]);
            $count = !empty($check) ? (int)($check[0]['c'] ?? 0) : 0;
            if ($count > 0) {
                jsonResponse(400, ['success' => false, 'error' => "Impossible de supprimer : {$count} produit(s) dans cette sous-categorie"]);
            }

            $db->query("DELETE FROM app_produits_sous_categories WHERE id_sous_categorie = ? AND id_entreprise = ?", [$id, $id_entreprise]);
            jsonResponse(200, ['success' => true]);
        }

        jsonResponse(404, ['success' => false, 'error' => 'Action POST inconnue']);
    }

    jsonResponse(405, ['success' => false, 'error' => 'Methode non autorisee']);

} catch (Throwable $e) {
    error_log("Erreur API Produits: " . $e->getMessage() . " | File: " . $e->getFile() . ":" . $e->getLine());
    jsonResponse(500, [
        'success' => false,
        'error' => 'Erreur serveur',
        'debug' => $e->getMessage(),
        'file'  => $e->getFile() . ':' . $e->getLine()
    ]);
}
