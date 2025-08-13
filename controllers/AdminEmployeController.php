<?php
require_once 'models/Employe.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    header('Location: index.php?page=accueil');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter'])) {
        Employe::ajouter($pdo, $_POST['email'], $_POST['username'], $_POST['mot_de_passe'], $_POST['prenom'], $_POST['nom']);
    } elseif (isset($_POST['supprimer'])) {
        Employe::supprimer($pdo, $_POST['supprimer']);
    } elseif (isset($_POST['reinitialiser'])) {
        Employe::reinitialiserMotDePasse($pdo, $_POST['reinitialiser'], $_POST['nouveau_mot_de_passe']);
    }

    header('Location: index.php?page=admin_employes');
    exit;
}

$employes = Employe::getAll($pdo);
require 'views/admin/employes.php';
