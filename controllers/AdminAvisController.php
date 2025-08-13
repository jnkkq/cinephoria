<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'models/Avis.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

if (isset($_POST['valider'])) {
    Avis::valider($pdo, $_POST['valider']);
} elseif (isset($_POST['supprimer'])) {
    Avis::supprimer($pdo, $_POST['supprimer']);
}

$avisEnAttente = Avis::getNonValides($pdo);
$avisValides = Avis::getValides($pdo);

require 'views/admin/avis.php';
?>
