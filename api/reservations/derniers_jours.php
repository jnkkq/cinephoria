<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../vendor/autoload.php';

// Vérifier si une session est déjà active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Vérifier l'authentification et les droits d'accès
if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Accès refusé. Droits insuffisants.'
    ]);
    exit;
}

try {
    // Connexion à MongoDB
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongoClient->cinema->reservations;

    // Calculer la date d'il y a 7 jours
    $dateDebut = new MongoDB\BSON\UTCDateTime(strtotime('-7 days') * 1000);
    
    // Requête pour récupérer les réservations des 7 derniers jours
    $pipeline = [
        [
            '$match' => [
                'date_reservation' => [
                    '$gte' => $dateDebut
                ]
            ]
        ],
        [
            '$group' => [
                '_id' => [
                    'film_id' => '$film_id',
                    'film_titre' => '$film_titre'
                ],
                'total_reservations' => ['$sum' => 1],
                'dernieres_reservations' => [
                    '$push' => [
                        'date' => '$date_reservation',
                        'utilisateur_id' => '$utilisateur_id'
                    ]
                ]
            ]
        ],
        [
            '$sort' => ['total_reservations' => -1]
        ]
    ];

    $result = $collection->aggregate($pipeline)->toArray();
    
    // Formater les résultats pour l'affichage
    $formattedResults = [];
    foreach ($result as $item) {
        // Convertir l'objet en tableau pour éviter les problèmes avec BSONArray
        $itemArray = (array)$item;
        $dernieresReservations = [];
        
        // Vérifier si 'dernieres_reservations' existe et n'est pas vide
        if (isset($itemArray['dernieres_reservations']) && is_iterable($itemArray['dernieres_reservations'])) {
            foreach ($itemArray['dernieres_reservations'] as $res) {
                $resArray = (array)$res;
                $dernieresReservations[] = [
                    'date' => $resArray['date']->toDateTime()->format('Y-m-d H:i:s'),
                    'utilisateur_id' => $resArray['utilisateur_id']
                ];
            }
        }
        
        $formattedResults[] = [
            'film_id' => $itemArray['_id']['film_id'],
            'titre' => $itemArray['_id']['film_titre'],
            'total_reservations' => $itemArray['total_reservations'],
            'dernieres_reservations' => $dernieresReservations
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $formattedResults,
        'periode' => '7 derniers jours'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
