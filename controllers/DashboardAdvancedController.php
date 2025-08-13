<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=login');
    exit;
}

// Inclure l'autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Connexion à MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongoClient->cinema->reservations;

// Connexion MySQL
$mysqli = new mysqli("localhost", "root", "", "cinephoria");
if ($mysqli->connect_errno) {
    die("Erreur MySQL: " . $mysqli->connect_error);
}

// Récupérer les réservations des 7 derniers jours
$dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
$sql = "SELECT r.id, r.utilisateur_id, r.seance_id, r.date_reservation, s.film_id, f.titre AS film_titre, s.date, s.heure_debut
        FROM reservations r
        JOIN seances s ON s.id = r.seance_id
        JOIN films f ON f.id = s.film_id
        WHERE r.date_reservation >= ?
        ORDER BY r.date_reservation DESC";
$stmt = $mysqli->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $dateDebut);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $docs = [];
        while ($row = $result->fetch_assoc()) {
            $docs[] = [
                'sql_id' => (int)$row['id'],
                'film_id' => (string)$row['film_id'],
                'film_titre' => $row['film_titre'],
                'date_reservation' => new MongoDB\BSON\UTCDateTime(strtotime($row['date_reservation']) * 1000),
                'utilisateur_id' => $row['utilisateur_id']
            ];
        }

        // Insérer dans MongoDB sans doublons
        if (!empty($docs)) {
            foreach ($docs as $doc) {
                $exists = $collection->findOne(['sql_id' => $doc['sql_id']]);
                if (!$exists) {
                    $collection->insertOne($doc);
                }
            }
        }
    }
    $stmt->close();
}
$mysqli->close();

// Inclure la vue du tableau de bord avancé
require 'views/admin/dashboard_advanced.php';
?>
