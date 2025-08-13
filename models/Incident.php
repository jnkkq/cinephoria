<?php
class Incident {
    // Récupérer tous les incidents d'une salle
    public static function getParSalle($pdo, $salle_id) {
        $stmt = $pdo->prepare("
            SELECT i.*, u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, 
                   s.numero as salle_numero, c.nom as cinema_nom
            FROM incidents i
            JOIN utilisateurs u ON i.utilisateur_id = u.id
            JOIN salles s ON i.salle_id = s.id
            JOIN cinemas c ON s.cinema_id = c.id
            WHERE i.salle_id = ?
            ORDER BY i.date_signalement DESC
        ");
        $stmt->execute([$salle_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer un incident par son ID
    public static function getById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT i.*, u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, 
                   s.numero as salle_numero, c.nom as cinema_nom
            FROM incidents i
            JOIN utilisateurs u ON i.utilisateur_id = u.id
            JOIN salles s ON i.salle_id = s.id
            JOIN cinemas c ON s.cinema_id = c.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Ajouter un nouvel incident
    public static function ajouter($pdo, $salle_id, $utilisateur_id, $description) {
        $stmt = $pdo->prepare("
            INSERT INTO incidents (salle_id, utilisateur_id, description, date_signalement)
            VALUES (?, ?, ?, NOW())
        ");
        $success = $stmt->execute([
            $salle_id,
            $utilisateur_id,
            $description
        ]);
        
        if ($success) {
            return $pdo->lastInsertId();
        }
        
        return false;
    }
    
    // Supprimer un incident
    public static function supprimer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM incidents WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
