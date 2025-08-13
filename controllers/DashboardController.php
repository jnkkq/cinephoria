<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'models/Film.php';
require_once 'models/Seance.php';
require_once 'models/Avis.php';
require_once 'models/Salle.php';
require_once 'models/Cinema.php';
require_once 'models/Employe.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

$cinemas = Cinema::getAll($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // FILMS
    if (isset($_POST['ajouter_film'])) {
        $genres = isset($_POST['genres']) ? $_POST['genres'] : [];
        Film::ajouter($pdo, $_POST['titre'], $_POST['description'], $_POST['age_minimum'], $_POST['affiche'], isset($_POST['coup_de_coeur']) ? 1 : 0, $genres);
    } elseif (isset($_POST['modifier_film'])) {
        $genres = isset($_POST['genres']) ? $_POST['genres'] : [];
        Film::modifier($pdo, $_POST['film_id'], $_POST['titre'], $_POST['description'], $_POST['age_minimum'], $_POST['affiche'], isset($_POST['coup_de_coeur']) ? 1 : 0, $genres);
    } elseif (isset($_POST['supprimer_film'])) {
        Film::supprimer($pdo, $_POST['film_id']);
    }

    // SEANCES
    elseif (isset($_POST['ajouter_seance'])) {
        Seance::ajouter($pdo, $_POST['film_id'], $_POST['salle_id'], $_POST['date'], $_POST['heure_debut'], $_POST['heure_fin'], $_POST['qualite'], $_POST['prix']);
    } elseif (isset($_POST['modifier_seance'])) {
        Seance::modifier($pdo, $_POST['seance_id'], $_POST['film_id'], $_POST['salle_id'], $_POST['date'], $_POST['heure_debut'], $_POST['heure_fin'], $_POST['qualite'], $_POST['prix']);
    } elseif (isset($_POST['supprimer_seance'])) {
        Seance::supprimer($pdo, $_POST['seance_id']);
    }

    // AVIS
    elseif (isset($_POST['valider_avis'])) {
        Avis::valider($pdo, $_POST['valider_avis']);
    } elseif (isset($_POST['supprimer_avis'])) {
        Avis::supprimer($pdo, $_POST['supprimer_avis']);
    }

    // EMPLOYES
    elseif (isset($_POST['ajouter_employe'])) {
        Employe::ajouter($pdo, $_POST['email'], $_POST['username'], $_POST['mot_de_passe'], $_POST['prenom'], $_POST['nom']);
    } elseif (isset($_POST['modifier_employe'])) {
        Employe::modifier($pdo, $_POST['employe_id'], $_POST['email'], $_POST['username'], $_POST['prenom'], $_POST['nom'], $_POST['role']);
    } elseif (isset($_POST['supprimer_employe'])) {
        Employe::supprimer($pdo, $_POST['employe_id']);
    }

    // SALLES
    elseif (isset($_POST['ajouter_salle'])) {
        Salle::ajouter($pdo, [
            'numero' => $_POST['numero'],
            'capacite' => $_POST['capacite'],
            'qualite_projection' => $_POST['qualite_projection'],
            'cinema_id' => $_POST['cinema_id']
        ]);
    } elseif (isset($_POST['modifier_salle'])) {
        Salle::modifier($pdo, [
            'id' => $_POST['id'],
            'numero' => $_POST['numero'],
            'capacite' => $_POST['capacite'],
            'qualite_projection' => $_POST['qualite_projection'],
            'cinema_id' => $_POST['cinema_id']
        ]);
    } elseif (isset($_POST['supprimer_salle'])) {
        Salle::supprimer($pdo, $_POST['id']);
    }

    header('Location: index.php?page=dashboard');
    exit;
}

// Chargement des données pour la vue
$films = Film::getAll($pdo);
$seances = Seance::getAll($pdo);
$avis = Avis::getNonValides($pdo);
$avis_valides = Avis::getValides($pdo);
$employes = Employe::getAll($pdo);
$salles = Salle::getAllAvecCinema($pdo);

// Récupérer tous les genres disponibles
$genres = [];
$stmt = $pdo->query("SELECT * FROM genres ORDER BY nom");
if ($stmt) {
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les genres pour chaque film
foreach ($films as &$film) {
    $film['genres'] = Film::getGenres($pdo, $film['id']);
}
unset($film); // Casser la référence

require 'views/admin/dashboard.php';
?>
