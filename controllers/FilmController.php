<?php
require_once 'models/Film.php';
require_once 'models/Genre.php';
require_once 'models/Cinema.php';

// Récupération des paramètres de filtre
$genre_id = $_GET['genre'] ?? null;
$cinema_id = $_GET['cinema'] ?? null;
$jour = !empty($_GET['jour']) ? $_GET['jour'] : null; // Pas de date par défaut

// Récupération des données pour les filtres
$genres = Genre::getAll($pdo);
$cinemas = Cinema::getAll($pdo);

// Récupération des films avec les filtres
$films = Film::getFiltered($pdo, [
    'genre' => $genre_id,
    'cinema_id' => $cinema_id,
    'jour' => $jour
]);

// Récupération des genres pour chaque film
foreach ($films as &$film) {
    $stmt = $pdo->prepare("
        SELECT g.nom 
        FROM genres g 
        JOIN film_genre fg ON g.id = fg.genre_id 
        WHERE fg.film_id = ?
    ");
    $stmt->execute([$film['id']]);
    $film['genres'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
unset($film); // Casser la référence

require 'views/pages/films.php';
?>
