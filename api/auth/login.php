<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once '../../config/config.php';
require_once '../../models/User.php';
require_once '../../utils/JWT.php';

// Gérer la requête OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// Récupérer les données JSON de la requête
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier les données d'entrée
if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
    exit();
}

try {
    // Vérifier les identifiants
    $user = User::verifierConnexion($pdo, $data['email'], $data['password']);
    
    if ($user) {
        // Créer un token JWT
        $token = JWT::encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);
        
        // Retourner les informations de l'utilisateur (sans le mot de passe) et le token
        unset($user['mot_de_passe']);
        echo json_encode([
            'success' => true, 
            'user' => $user,
            'token' => $token
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false, 
            'message' => 'Email ou mot de passe incorrect'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>
