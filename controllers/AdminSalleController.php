<?php
require_once 'models/Salle.php';
require_once 'models/Cinema.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

$cinemas = Cinema::getAll($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_salle'])) {
        Salle::ajouter($pdo, $_POST);
    } elseif (isset($_POST['modifier_salle'])) {
        Salle::modifier($pdo, $_POST);
    } elseif (isset($_POST['supprimer_salle'])) {
        Salle::supprimer($pdo, $_POST['id']);
    }
    header('Location: index.php?page=admin_salles');
    exit;
}

$salles = Salle::getAllAvecCinema($pdo);
require 'views/admin/salles.php';
