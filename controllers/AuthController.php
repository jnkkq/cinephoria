<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/User.php';

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    $utilisateur = User::verifierConnexion($pdo, $identifiant, $motDePasse);

    if ($utilisateur) {
        $_SESSION['utilisateur'] = $utilisateur;
        header('Location: index.php?page=mon_espace');
        exit;
    } else {
        $erreur = "Identifiants invalides.";
    }
}

require 'views/pages/login.php';
?>
