<?php
// Copie du fichier fourni par l'utilisateur, adapté pour le projet
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/auth.php';

class ClientsAPI {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'];
        // Ajout d'un paramètre aléatoire à chaque requête
        if (!isset($_GET['_rand'])) {
            $_GET['_rand'] = time() . '_' . bin2hex(random_bytes(4));
        }

        // Nouvelle gestion : récupération sécurisée via le token JWT
        $user = getUserFromToken();
        $user_id = $user['id'];
        $id_entreprise = $user['id_entreprise'];
        $role = in_array('ALL', $user['access']) ? 'admin' : 'user';

        try {
            switch ($action) {
                // CLIENTS
                case 'list_clients':
                    $this->listClients($id_entreprise, $role);
                    break;
                case 'get_client':
                    $this->getClient();
                    break;
                case 'add_client':
                    $this->addClient($id_entreprise, $user_id);
                    break;
                case 'update_client':
                    $this->updateClient($id_entreprise, $user_id);
                    break;
                case 'delete_client':
                    $this->deleteClient();
                    break;
                case 'stats_client':
                    $this->getStatsClient();
                    break;
                case 'search_clients':
                    $this->searchClients($id_entreprise, $role);
                    break;

                // CREDITS
                case 'list_credits':
                    $this->listCredits();
                    break;
                case 'get_credit':
                    $this->getCredit();
                    break;
                case 'add_credit':
                    $this->addCredit();
                    break;
                case 'update_credit':
                    $this->updateCredit();
                    break;
                case 'credits_en_cours':
                    $this->getCreditsEnCours($id_entreprise, $role);
                    break;
                case 'credits_retard':
                    $this->getCreditsEnRetard();
                    break;
                case 'top_dettes':
                    $this->getTopDettes();
                    break;

                // PAIEMENTS
                case 'add_paiement':
                    $this->addPaiement();
                    break;
                case 'historique_paiements':
                    $this->getHistoriquePaiements();
                    break;

                // RAPPORTS
                case 'rapport_client':
                    $this->getRapportClient();
                    break;
                case 'rapport_global':
                    $this->getRapportGlobal($id_entreprise, $role);
                    break;

                default:
                    $this->sendResponse(404, [
                        'success' => false,
                        'error' => 'Action non trouvee'
                    ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, [
                'success' => false,
                'error' => 'Erreur serveur',
                'details' => $e->getMessage()
            ]);
        }
    }

    // ...existing code from attachment...
}

$api = new ClientsAPI();
$api->handleRequest();
