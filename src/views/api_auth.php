<?php
$allowed_origins = [
    'http://localhost:5173',
    'http://localhost:5174',
    'http://localhost:5175',
    'http://127.0.0.1:5173',
    'http://127.0.0.1:5174',
    'http://127.0.0.1:5175'
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

require_once 'config/database.php';

class AuthAPI {
    private $db;

    public function __construct() {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur de connexion à la base de données',
                'details' => $e->getMessage()
            ]);
        }
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? 'login';

        switch ($method) {
            case 'POST':
                $this->handlePost($action);
                break;
            case 'GET':
                $this->handleGet($action);
                break;
            default:
                $this->sendResponse(405, ['success' => false, 'error' => 'Méthode non autorisée']);
        }
    }

    private function handlePost($action) {
        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
            case 'register2':
                $this->register2();
                break;
            default:
                $this->sendResponse(404, ['success' => false, 'error' => 'Action POST inconnue']);
        }
    }

    private function handleGet($action) {
        switch ($action) {
            case 'verify':
                $this->verifyToken();
                break;
            case 'user':
                $this->getUserInfo();
                break;
            default:
                $this->sendResponse(404, ['success' => false, 'error' => 'Action GET inconnue']);
        }
    }

    /**
     * POST - Connexion utilisateur
     */
    private function login() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, ['success' => false, 'error' => 'JSON invalide']);
        }

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->sendResponse(400, [
                'success' => false,
                'error' => 'Email et mot de passe requis'
            ]);
        }

        try {
            $sql = "SELECT * FROM app_utilisateurs WHERE email = ? AND statut = 'actif'";
            $users = $this->db->query($sql, [$email]);

            if (empty($users)) {
                $this->sendResponse(401, [
                    'success' => false,
                    'error' => 'Email ou mot de passe incorrect'
                ]);
            }

            $user = $users[0];

            // Vérifier le mot de passe
            if (!password_verify($password, $user['mot_de_passe'])) {
                $this->sendResponse(401, [
                    'success' => false,
                    'error' => 'Email ou mot de passe incorrect'
                ]);
            }

            // Générer un token simple (vous pouvez utiliser JWT pour plus de sécurité)
            $token = bin2hex(random_bytes(32));

            // Parser les accès
            $accesData = $this->parseAcces($user);

            // Préparer les données utilisateur (sans le mot de passe)
            unset($user['mot_de_passe']);
            
            // S'assurer que le champ 'role' existe (mapper id_role vers role si nécessaire)
            if (!isset($user['role']) && isset($user['id_role'])) {
                $user['role'] = $user['id_role'];
            }
            
            $user['acces'] = $accesData;
            $user['token'] = $token;

            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => $user
            ]);

        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur lors de la connexion',
                'details' => $e->getMessage()
            ]);
        }
    }

    /**
     * POST - Inscription d'un nouvel utilisateur
     */
    private function register() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, ['success' => false, 'error' => 'JSON invalide']);
        }

        // Validation des champs requis
        $required = ['nom', 'prenom', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->sendResponse(400, [
                    'success' => false,
                    'error' => "Le champ {$field} est requis"
                ]);
            }
        }

        $email = trim($input['email']);
        
        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(400, [
                'success' => false,
                'error' => 'Email invalide'
            ]);
        }

        // Vérifier si l'email existe déjà
        try {
            $checkSql = "SELECT id FROM app_utilisateurs WHERE email = ?";
            $existing = $this->db->query($checkSql, [$email]);

            if (!empty($existing)) {
                $this->sendResponse(400, [
                    'success' => false,
                    'error' => 'Cet email est déjà utilisé'
                ]);
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);

            // Préparer les accès (par défaut vide pour un nouvel utilisateur)
            $accesMagasins = $input['acces_magasins'] ?? '';
            $accesEntrepots = $input['acces_entrepots'] ?? '';
            $accesClients = isset($input['acces_clients']) ? ($input['acces_clients'] ? 1 : 0) : 0;
            $accesFournisseurs = isset($input['acces_fournisseurs']) ? ($input['acces_fournisseurs'] ? 1 : 0) : 0;

            // Si c'est un tableau, convertir en chaîne séparée par des virgules
            if (is_array($accesMagasins)) {
                $accesMagasins = implode(',', $accesMagasins);
            }
            if (is_array($accesEntrepots)) {
                $accesEntrepots = implode(',', $accesEntrepots);
            }

            $sql = "INSERT INTO app_utilisateurs 
                    (nom, prenom, email, mot_de_passe, contact, photo, id_role, id_entreprise, access, statut, date_create, create_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
            
            $params = [
                $input['nom'],
                $input['prenom'],
                $email,
                $hashedPassword,
                $input['telephone'] ?? '',
                $input['photo'],
                $input['role'] ?? '11',
                $input['entreprise'] ?? 'nexcode',
                $input['access'],
                $input['statut'],
                $input['user_id'] ?? '1',
            ];

            $this->db->query($sql, $params);
            $userId = $this->db->lastInsertId();

            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => ['id' => $userId]
            ]);

        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur lors de la création de l\'utilisateur',
                'details' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * POST - Inscription d'un nouvel utilisateur (Version 2)
     */
    private function register2() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(400, ['success' => false, 'error' => 'JSON invalide']);
        }

        // Validation des champs requis
        $required = ['nom', 'prenom', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->sendResponse(400, [
                    'success' => false,
                    'error' => "Le champ {$field} est requis"
                ]);
            }
        }

        $email = trim($input['email']);
        
        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(400, [
                'success' => false,
                'error' => 'Email invalide'
            ]);
        }

        // Vérifier si l'email existe déjà
        try {
            $checkSql = "SELECT id FROM app_utilisateurs WHERE email = ?";
            $existing = $this->db->query($checkSql, [$email]);

            if (!empty($existing)) {
                $this->sendResponse(400, [
                    'success' => false,
                    'error' => 'Cet email est déjà utilisé'
                ]);
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);

            // Préparer id_entreprise (NULL si non fourni, car la contrainte FK permet NULL)
            $id_entreprise = null;
            if (isset($input['id_entreprise']) && !empty($input['id_entreprise'])) {
                $id_entreprise = (int)$input['id_entreprise'];
                // Vérifier que l'entreprise existe dans la base
                $checkEntreprise = "SELECT id_entreprise FROM app_entreprises WHERE id_entreprise = ?";
                $entrepriseExists = $this->db->query($checkEntreprise, [$id_entreprise]);
                if (empty($entrepriseExists)) {
                    // Si l'entreprise n'existe pas, utiliser NULL
                    $id_entreprise = null;
                }
            }
            
            // Préparer access (doit être une chaîne JSON)
            $access = $input['access'] ?? '["ALL_TEST"]';
            if (is_array($access)) {
                $access = json_encode($access);
            }

            // Préparer create_by (NULL si l'utilisateur n'existe pas, car la contrainte FK permet NULL)
            $create_by = null;
            if (isset($input['user_id']) && !empty($input['user_id'])) {
                $user_id = (int)$input['user_id'];
                // Vérifier que l'utilisateur existe dans la base
                $checkUser = "SELECT id FROM app_utilisateurs WHERE id = ?";
                $userExists = $this->db->query($checkUser, [$user_id]);
                if (!empty($userExists)) {
                    $create_by = $user_id;
                }
            }

            $sql = "INSERT INTO app_utilisateurs 
                    (nom, prenom, email, mot_de_passe, contact, photo, id_role, id_entreprise, access, statut, date_create, create_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
            
            $params = [
                $input['nom'],
                $input['prenom'],
                $email,
                $hashedPassword,
                $input['telephone'] ?? '',
                $input['photo'] ?? '',
                isset($input['role']) ? (int)$input['role'] : 1,
                $id_entreprise, // NULL ou ID existant
                $access, // Chaîne JSON
                $input['statut'] ?? 'actif',
                $create_by, // NULL ou ID utilisateur existant
            ];

            $this->db->query($sql, $params);
            $userId = $this->db->lastInsertId();

            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => ['id' => $userId]
            ]);

        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur lors de la création de l\'utilisateur',
                'details' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * GET - Récupérer les informations de l'utilisateur connecté
     */
    private function getUserInfo() {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $this->sendResponse(400, ['success' => false, 'error' => 'ID utilisateur requis']);
        }

        try {
            $sql = "SELECT id, nom, prenom, email, telephone, photo, id_role, 
                           access, statut, date_create, create_by
                    FROM app_utilisateurs 
                    WHERE id = ? AND statut = 'actif'";
            
            $users = $this->db->query($sql, [$userId]);

            if (empty($users)) {
                $this->sendResponse(404, [
                    'success' => false,
                    'error' => 'Utilisateur non trouvé'
                ]);
            }

            $user = $users[0];
            $user['acces'] = $this->parseAcces($user);

            $this->sendResponse(200, [
                'success' => true,
                'data' => $user
            ]);

        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur lors de la récupération des informations',
                'details' => $e->getMessage()
            ]);
        }
    }

    /**
     * Parser les accès de l'utilisateur
     */
    private function parseAcces($user) {
        // Si la colonne access existe et contient du JSON, le parser
        if (isset($user['access']) && !empty($user['access'])) {
            $accessJson = is_string($user['access']) ? $user['access'] : json_encode($user['access']);
            $decoded = json_decode($accessJson, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Convertir les codes de permission en format attendu par le frontend
                $acces = [
                    'magasins' => [],
                    'entrepots' => [],
                    'clients' => false,
                    'fournisseurs' => false
                ];
                
                // Si l'utilisateur a ALL ou ALL_TEST, donner accès à tout
                if (in_array('ALL', $decoded) || in_array('ALL_TEST', $decoded)) {
                    $acces['magasins'] = 'all';
                    $acces['entrepots'] = 'all';
                    $acces['clients'] = true;
                    $acces['fournisseurs'] = true;
                } else {
                    // Mapper les codes de permission vers les modules
                    // GT = Gestion entrepot
                    if (in_array('GT', $decoded)) {
                        $acces['entrepots'] = 'all';
                    }
                    
                    // GCL = Gestion clients
                    if (in_array('GCL', $decoded)) {
                        $acces['clients'] = true;
                    }
                    
                    // GF = Gestion fournisseur
                    if (in_array('GF', $decoded)) {
                        $acces['fournisseurs'] = true;
                    }
                    
                    // GS, GV, GP peuvent donner accès aux magasins (à adapter selon votre logique)
                    if (in_array('GS', $decoded) || in_array('GV', $decoded) || in_array('GP', $decoded)) {
                        $acces['magasins'] = 'all';
                    }
                }
                
                return $acces;
            }
        }
        
        // Fallback : retourner un objet vide avec la structure attendue
        return [
            'magasins' => [],
            'entrepots' => [],
            'clients' => false,
            'fournisseurs' => false
        ];
    }

    /**
     * Vérifier un token
     */
    private function verifyToken() {
        // Cette fonction peut être étendue avec JWT
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Token valide'
        ]);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}

// Démarrage de l'API
$api = new AuthAPI();
$api->handleRequest();