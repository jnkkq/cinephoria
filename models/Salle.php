<?php
class Salle {
    public static function getAll($pdo) {
        $stmt = $pdo->query("
            SELECT s.*, c.nom AS cinema
            FROM salles s
            JOIN cinemas c ON s.cinema_id = c.id
            ORDER BY c.nom, s.numero
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getAllAvecCinema($pdo) {
        $stmt = $pdo->query("
            SELECT s.*, c.nom AS cinema_nom
            FROM salles s
            JOIN cinemas c ON s.cinema_id = c.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($pdo, $data) {
        $stmt = $pdo->prepare("INSERT INTO salles (numero, capacite, qualite_projection, cinema_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['numero'], $data['capacite'], $data['qualite_projection'], $data['cinema_id']]);
        // Récupérer l'id de la salle nouvellement créée
        $salle_id = $pdo->lastInsertId();
        // Créer les sièges
        $capacite = (int)$data['capacite'];
        $stmtSiege = $pdo->prepare("INSERT INTO sieges (numero, salle_id) VALUES (?, ?)");
        for ($i = 1; $i <= $capacite; $i++) {
            $stmtSiege->execute([$i, $salle_id]);
        }
    }

    public static function modifier($pdo, $data) {
        $stmt = $pdo->prepare("UPDATE salles SET numero = ?, capacite = ?, qualite_projection = ?, cinema_id = ? WHERE id = ?");
        $stmt->execute([$data['numero'], $data['capacite'], $data['qualite_projection'], $data['cinema_id'], $data['id']]);
    }

    public static function supprimer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM salles WHERE id = ?");
        $stmt->execute([$id]);
    }
    
}
?>
