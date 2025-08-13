<?php
// Affichage des erreurs pour debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion MySQL
$mysqli = new mysqli("localhost", "root", "", "cinephoria"); // adapte si besoin
if ($mysqli->connect_errno) {
    die("Erreur MySQL: " . $mysqli->connect_error);
}

// Connexion MongoDB
require __DIR__ . '/../vendor/autoload.php';
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongoClient->cinema->reservations;

// Optionnel : vider la collection avant import (décommente si tu veux tout remplacer)
// $collection->deleteMany([]);

// Récupérer les réservations des 7 derniers jours
$dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
$sql = "SELECT r.id, r.utilisateur_id, r.seance_id, r.date_reservation, s.film_id, f.titre AS film_titre, s.date, s.heure_debut
        FROM reservations r
        JOIN seances s ON s.id = r.seance_id
        JOIN films f ON f.id = s.film_id
        WHERE r.date_reservation >= ?
        ORDER BY r.date_reservation DESC";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Erreur préparation requête: " . $mysqli->error);
}
$stmt->bind_param('s', $dateDebut);
if (!$stmt->execute()) {
    die("Erreur exécution requête: " . $stmt->error);
}
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
$inserted = 0;
if (!empty($docs)) {
    foreach ($docs as $doc) {
        $exists = $collection->findOne(['sql_id' => $doc['sql_id']]);
        if (!$exists) {
            $collection->insertOne($doc);
            $inserted++;
        }
    }
    if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
        echo "Importation terminée ! $inserted nouvelles réservations ajoutées à MongoDB.";
    }
} else {
    if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
        echo "Aucune réservation trouvée en base SQL.";
    }
}
?>
