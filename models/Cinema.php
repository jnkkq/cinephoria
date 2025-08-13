<?php
class Cinema {
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT id, nom FROM cinemas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getByFilm($pdo, $filmId) {
        $stmt = $pdo->prepare("
            SELECT DISTINCT c.id, c.nom 
            FROM cinemas c
            JOIN salles s ON s.cinema_id = c.id
            JOIN seances se ON se.salle_id = s.id
            WHERE se.film_id = ?
            ORDER BY c.nom
        ");
        $stmt->execute([$filmId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
