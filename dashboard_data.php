<?php
require_once __DIR__ . '/script-test/import_to_mongo.php'; // Import automatique à chaque appel du dashboard
require 'vendor/autoload.php'; // Assure-toi d'avoir fait "composer require mongodb/mongodb"

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->cinema->reservations;

// Date il y a 7 jours
$date_7_days_ago = new MongoDB\BSON\UTCDateTime((new DateTime('-7 days'))->getTimestamp()*1000);

$pipeline = [
    ['$match' => ['date_reservation' => ['$gte' => $date_7_days_ago]]],
    ['$group' => [
        '_id' => ['$concat' => ['$film_id', ' - ', '$film_titre']],
        'nombre_reservations' => ['$sum' => 1]
    ]],
    ['$sort' => ['nombre_reservations' => -1]]
];

$result = $collection->aggregate($pipeline);

$data = [];
foreach ($result as $row) {
    $data[] = [
        'film' => $row->_id,
        'nombre_reservations' => $row->nombre_reservations
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
