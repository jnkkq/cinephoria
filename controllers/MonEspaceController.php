<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/Reservation.php';
require_once 'models/Avis.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=login');
    exit;
}

// Traitement de l'ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_avis') {
    $film_id = $_POST['film_id'] ?? null;
    $note = $_POST['note'] ?? null;
    $commentaire = $_POST['commentaire'] ?? '';
    
    if ($film_id && $note) {
        Avis::ajouter($pdo, $film_id, $_SESSION['utilisateur']['id'], $note, $commentaire);
        // Rediriger pour éviter le rechargement du formulaire
        header('Location: index.php?page=mon_espace&avis_ajoute=1');
        exit;
    }
}

// Récupérer les réservations avec les informations sur les films
$reservations = Reservation::getParUtilisateur($pdo, $_SESSION['utilisateur']['id']);

// Fonction pour vérifier si une séance est passée
function estSeancePassee($date, $heure) {
    try {
        // Pour le débogage, forcer le retour à true pour tous les films
        // À supprimer une fois le débogage terminé
        return true;
        
        /*
        // Définir le fuseau horaire
        $timezone = new DateTimeZone('Europe/Paris');
        
        // Créer l'objet DateTime pour maintenant
        $maintenant = new DateTime('now', $timezone);
        
        // Nettoyer et formater l'heure
        $heure = trim($heure);
        if (strpos($heure, ':') === false) {
            $heure .= ':00';
        }
        
        // Créer l'objet DateTime pour la séance
        $dateSeance = DateTime::createFromFormat(
            'Y-m-d H:i:s', 
            $date . ' ' . $heure . ':00',
            $timezone
        );
        
        if ($dateSeance === false) {
            error_log("Erreur de format de date: " . $date . ' ' . $heure);
            return false;
        }
        
        // Ajouter 30 minutes à la fin de la séance
        $dateSeance->add(new DateInterval('PT30M'));
        
        // Pour le débogage
        error_log("Maintenant: " . $maintenant->format('Y-m-d H:i:s'));
        error_log("Fin séance: " . $dateSeance->format('Y-m-d H:i:s'));
        error_log("Séance passée: " . ($maintenant > $dateSeance ? 'oui' : 'non'));
        
        return $maintenant > $dateSeance;
        */
        
    } catch (Exception $e) {
        error_log("Erreur dans estSeancePassee: " . $e->getMessage());
        return false;
    }
}

// Vérifier si un avis a été ajouté avec succès
$avisAjoute = isset($_GET['avis_ajoute']) && $_GET['avis_ajoute'] == 1;

require 'views/pages/mon_espace.php';
?>
