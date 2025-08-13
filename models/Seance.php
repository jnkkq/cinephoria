<?php
class Seance {
    public static function getAll($pdo) {
        $stmt = $pdo->query("
            SELECT s.*, f.titre AS film, sa.numero AS salle, c.nom AS cinema
            FROM seances s
            JOIN films f ON s.film_id = f.id
            JOIN salles sa ON s.salle_id = sa.id
            JOIN cinemas c ON sa.cinema_id = c.id
            ORDER BY s.date DESC, s.heure_debut DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($pdo, $film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix) {
        $stmt = $pdo->prepare("
            INSERT INTO seances (film_id, salle_id, date, heure_debut, heure_fin, qualite, prix)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix]);
    }

    public static function modifier($pdo, $id, $film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix) {
        $stmt = $pdo->prepare("
            UPDATE seances SET film_id = ?, salle_id = ?, date = ?, heure_debut = ?, heure_fin = ?, qualite = ?, prix = ?
            WHERE id = ?
        ");
        $stmt->execute([$film_id, $salle_id, $date, $heure_debut, $heure_fin, $qualite, $prix, $id]);
    }

    public static function supprimer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM seances WHERE id = ?");
        $stmt->execute([$id]);
    }


    public static function getByFilmAndCinema($pdo, $filmId, $cinemaId) {
        $stmt = $pdo->prepare("
            SELECT s.*, sa.numero AS salle_numero, s.date
            FROM seances s
            JOIN salles sa ON sa.id = s.salle_id
            WHERE s.film_id = ? AND sa.cinema_id = ?
            ORDER BY s.date, s.heure_debut
        ");
        $stmt->execute([$filmId, $cinemaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
