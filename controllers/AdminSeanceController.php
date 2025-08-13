<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'models/Seance.php';
require_once 'models/Film.php';
require_once 'models/Salle.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

$films = Film::getAll($pdo);
$salles = Salle::getAll($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $film_id = $_POST['film_id'];
    $salle_id = $_POST['salle_id'];
    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $qualite = $_POST['qualite'];
    $prix = $_POST['prix'];

    if (isset($_POST['ajouter'])) {
        Seance::ajouter($pdo, $film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix);
    }

    if (isset($_POST['modifier']) && !empty($_POST['id'])) {
        Seance::modifier($pdo, $_POST['id'], $film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix);
    }

    if (isset($_POST['supprimer'])) {
        Seance::supprimer($pdo, $_POST['supprimer']);
    }

    header("Location: index.php?page=admin_seances");
    exit;
}

$seances = Seance::getAll($pdo);
require 'views/admin/seances.php';
?>
