<?php
// Script pour générer les sièges manquants selon la capacité de chaque salle
require_once 'config/config.php';

// Récupérer toutes les salles
$salles = $pdo->query("SELECT id, capacite FROM salles")->fetchAll(PDO::FETCH_ASSOC);

foreach ($salles as $salle) {
    $salle_id = $salle['id'];
    $capacite = (int)$salle['capacite'];
    // Compter les sièges déjà présents
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sieges WHERE salle_id = ?");
    $stmt->execute([$salle_id]);
    $nb_sieges = (int)$stmt->fetchColumn();

    // Ajouter les sièges manquants
    if ($nb_sieges < $capacite) {
        for ($i = $nb_sieges + 1; $i <= $capacite; $i++) {
            $stmt = $pdo->prepare("INSERT INTO sieges (numero, salle_id) VALUES (?, ?)");
            $stmt->execute([$i, $salle_id]);
        }
        echo "Salle $salle_id : ajout de ".($capacite - $nb_sieges)." sièges.\n";
    } else {
        echo "Salle $salle_id : OK ($nb_sieges/$capacite sièges)\n";
    }
}

echo "Terminé !\n";
