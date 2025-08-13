<?php
session_start();
require_once 'config/config.php';
require_once 'models/Reservation.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=login');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur']['id'];
$seance_id = $_POST['seance_id'] ?? null;
$sieges = $_POST['sieges'] ?? [];

if (!$seance_id || empty($sieges)) {
    echo "<p>Erreur : Veuillez sélectionner au moins un siège.</p>";
    exit;
}

$total_places = count($sieges);

// Récupérer le prix de la séance
$stmt = $pdo->prepare("SELECT prix FROM seances WHERE id = ?");
$stmt->execute([$seance_id]);
$prix = $stmt->fetchColumn();
$total_prix = $total_places * $prix;

// Enregistrer la réservation
$reservation_id = Reservation::creer($pdo, $utilisateur_id, $seance_id, $total_places, $total_prix);

// Enregistrer les sièges réservés
Reservation::enregistrerPlaces($pdo, $reservation_id, $sieges);

// Redirection ou message de succès
header('Location: index.php?page=mon_espace');
exit;
?>
