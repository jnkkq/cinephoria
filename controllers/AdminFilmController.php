<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'models/Film.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $age_minimum = $_POST['age_minimum'];
    $affiche = $_POST['affiche'];
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;

    if (isset($_POST['ajouter'])) {
        Film::ajouter($pdo, $titre, $description, $age_minimum, $affiche, $coup_de_coeur);
    }

    if (isset($_POST['modifier']) && !empty($_POST['id'])) {
        Film::modifier($pdo, $_POST['id'], $titre, $description, $age_minimum, $affiche, $coup_de_coeur);
    }

    if (isset($_POST['supprimer'])) {
        Film::supprimer($pdo, $_POST['supprimer']);
    }

    header("Location: index.php?page=admin_films");
    exit;
}

$films = Film::getAll($pdo);

require 'views/admin/films.php';
?>
