<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Incident.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

// Vérifier la méthode de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Gérer les requêtes OPTIONS (prévol)
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier l'authentification via JWT
$authResult = AuthMiddleware::authenticate();
if (!$authResult) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit();
}

try {
    $pdo = getDbConnection();
    
    // Gérer les différentes méthodes HTTP
    switch ($method) {
        case 'GET':
            // Récupérer les incidents pour une salle
            $salle_id = isset($_GET['salle_id']) ? (int)$_GET['salle_id'] : null;
            
            if (!$salle_id) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'error' => 'ID de salle manquant']);
                exit();
            }
            
            $incidents = Incident::getParSalle($pdo, $salle_id);
            echo json_encode(['success' => true, 'data' => $incidents]);
            break;
            
        case 'POST':
            // Ajouter un nouvel incident
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['salle_id']) || !isset($data['description'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'error' => 'Données manquantes']);
                exit();
            }
            
            $salle_id = (int)$data['salle_id'];
            $description = trim($data['description']);
            $utilisateur_id = $_SESSION['utilisateur']['id'];
            
            $incident_id = Incident::ajouter($pdo, $salle_id, $utilisateur_id, $description);
            
            if ($incident_id) {
                $incident = Incident::getById($pdo, $incident_id);
                echo json_encode(['success' => true, 'data' => $incident]);
            } else {
                throw new Exception("Échec de l'ajout de l'incident");
            }
            break;
            
        case 'DELETE':
            // Supprimer un incident
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'error' => 'ID de l\'incident manquant']);
                exit();
            }
            
            $incident_id = (int)$data['id'];
            
            // Vérifier si l'utilisateur est admin ou a les droits
            if (!in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
                http_response_code(403); // Forbidden
                echo json_encode(['success' => false, 'error' => 'Non autorisé']);
                exit();
            }
            
            $success = Incident::supprimer($pdo, $incident_id);
            
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Échec de la suppression de l'incident");
            }
            break;
            
        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>
