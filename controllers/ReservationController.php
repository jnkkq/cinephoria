<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once 'models/Film.php';
require_once 'models/Cinema.php';
require_once 'models/Seance.php';

$cinemas = Cinema::getAll($pdo);
$films = [];
$seances = [];

$selectedCinema = $_GET['cinema'] ?? '';
$selectedFilm = $_GET['film'] ?? '';

if ($selectedCinema) {
    $films = Film::getByCinema($pdo, $selectedCinema);
    
    // Si un film est sélectionné mais pas de cinéma, on récupère quand même les films
    if ($selectedFilm) {
        $seances = Seance::getByFilmAndCinema($pdo, $selectedFilm, $selectedCinema);
    }
} elseif ($selectedFilm) {
    // Si seul le film est sélectionné, on récupère les cinémas qui le diffusent
    $films = Film::getAll($pdo);
    $cinemas = Cinema::getByFilm($pdo, $selectedFilm);
}

if (isset($_GET['seance'])) {
    require_once 'models/Siege.php';
    require_once 'models/Reservation.php';

    $seanceId = $_GET['seance'];

    $stmt = $pdo->prepare("SELECT salle_id FROM seances WHERE id = ?");
    $stmt->execute([$seanceId]);
    $salleId = $stmt->fetchColumn();

    $sieges = Siege::getBySalle($pdo, $salleId);
    $occupes = Reservation::getSiegesReserves($pdo, $seanceId);

    require 'views/pages/choix_sieges.php';
    exit;
}

require 'views/pages/reservation.php';
?>
