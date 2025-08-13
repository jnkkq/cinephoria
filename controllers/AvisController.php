<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'models/Avis.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $film_id = $_POST['film_id'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'] ?? '';
    $utilisateur_id = $_SESSION['utilisateur']['id'];

    Avis::ajouter($pdo, $film_id, $utilisateur_id, $note, $commentaire);

    header("Location: index.php?page=films&avis=ok");
    exit;
}
?>
