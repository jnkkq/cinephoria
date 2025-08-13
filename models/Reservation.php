<?php
class Reservation {
    public static function getSiegesReserves($pdo, $seanceId) {
        $stmt = $pdo->prepare("
            SELECT siege_id FROM places_reservees
            JOIN reservations ON reservations.id = places_reservees.reservation_id
            WHERE reservations.seance_id = ?
        ");
        $stmt->execute([$seanceId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'siege_id');
    }

    public static function creer($pdo, $utilisateur_id, $seance_id, $nombre_places, $total_prix) {
        $stmt = $pdo->prepare("
            INSERT INTO reservations (utilisateur_id, seance_id, nombre_places, total_prix, statut)
            VALUES (?, ?, ?, ?, 'valide')
        ");
        $stmt->execute([$utilisateur_id, $seance_id, $nombre_places, $total_prix]);
        return $pdo->lastInsertId();
    }

    public static function enregistrerPlaces($pdo, $reservation_id, $sieges) {
        $stmt = $pdo->prepare("
            INSERT INTO places_reservees (reservation_id, siege_id) VALUES (?, ?)
        ");
        foreach ($sieges as $siege_id) {
            $stmt->execute([$reservation_id, $siege_id]);
        }
    }

    public static function getParUtilisateur($pdo, $utilisateur_id) {
        // Récupérer d'abord les réservations
        $stmt = $pdo->prepare("
            SELECT r.*, s.date, s.heure_debut, s.heure_fin, f.titre, sa.numero AS salle_numero, c.nom AS cinema_nom
            FROM reservations r
            JOIN seances s ON s.id = r.seance_id
            JOIN films f ON f.id = s.film_id
            JOIN salles sa ON sa.id = s.salle_id
            JOIN cinemas c ON c.id = sa.cinema_id
            WHERE r.utilisateur_id = ?
            ORDER BY s.date DESC, s.heure_debut DESC
        ");
        $stmt->execute([$utilisateur_id]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pour chaque réservation, récupérer les numéros de siège
        foreach ($reservations as &$reservation) {
            $stmt = $pdo->prepare("
                SELECT sieges.numero 
                FROM places_reservees pr
                JOIN sieges ON sieges.id = pr.siege_id
                WHERE pr.reservation_id = ?
                ORDER BY sieges.numero
            ");
            $stmt->execute([$reservation['id']]);
            $sieges = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $reservation['sieges'] = $sieges;
        }

        return $reservations;
    }
}
?>
