<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');  // Cache preflight request for 24 hours
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/config.php';
require_once '../../models/Reservation.php';

// Vérifier que la méthode est bien GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// Récupérer l'ID de l'utilisateur depuis les paramètres de requête
$utilisateurId = $_GET['utilisateur_id'] ?? null;

if (!$utilisateurId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
    exit();
}

try {
    // Récupérer les réservations de l'utilisateur avec les détails complets
    $sql = "
        SELECT 
            r.id,
            r.total_prix,
            r.statut,
            f.titre,
            s.date,
            s.heure_debut,
            sa.numero AS salle_numero,
            c.nom AS cinema_nom,
            COUNT(pr.id) AS nombre_places,
            GROUP_CONCAT(sie.numero ORDER BY sie.numero SEPARATOR ', ') AS numeros_sieges
        FROM reservations r
        JOIN seances s ON r.seance_id = s.id
        JOIN films f ON s.film_id = f.id
        JOIN salles sa ON s.salle_id = sa.id
        JOIN cinemas c ON sa.cinema_id = c.id
        LEFT JOIN places_reservees pr ON r.id = pr.reservation_id
        LEFT JOIN sieges sie ON pr.siege_id = sie.id
        WHERE r.utilisateur_id = ?
        GROUP BY r.id
        ORDER BY s.date DESC, s.heure_debut DESC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$utilisateurId]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les données pour la réponse
    $seances = [];
    foreach ($reservations as $reservation) {
        $seances[] = [
            'id' => $reservation['id'],
            'film_titre' => $reservation['titre'] ?? 'Film inconnu',
            'date' => $reservation['date'],
            'heure_debut' => $reservation['heure_debut'],
            'salle_numero' => $reservation['salle_numero'] ?? null,
            'cinema_nom' => $reservation['cinema_nom'] ?? null,
            'nombre_places' => (int)$reservation['nombre_places'],
            'prix_total' => (float)$reservation['total_prix'],
            'statut' => $reservation['statut'] ?? 'valide',
            'sieges' => !empty($reservation['numeros_sieges']) ? $reservation['numeros_sieges'] : 'Non spécifié'
        ];
    }
    
    echo json_encode([
        'success' => true,
        'seances' => $seances
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des séances',
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
