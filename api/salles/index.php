<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Salle.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

// Vérifier la méthode de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Gérer les requêtes OPTIONS (prévol)
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier que la méthode est bien GET
if ($method !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
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
    // Récupérer toutes les salles
    $pdo = getDbConnection();
    $salles = Salle::getAll($pdo);
    
    // Formater la réponse
    $response = [
        'success' => true,
        'data' => $salles
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des salles: ' . $e->getMessage()
    ]);
}
