<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/User.php';

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';

    if ($email && $username && $mot_de_passe && $prenom && $nom) {
        try {
            User::creer($pdo, [
                'email' => $email,
                'username' => $username,
                'mot_de_passe' => $mot_de_passe,
                'prenom' => $prenom,
                'nom' => $nom
            ]);
            header('Location: index.php?page=login');
            exit;
        } catch (PDOException $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Tous les champs sont obligatoires.";
    }
}

require 'views/pages/register.php';
?>
