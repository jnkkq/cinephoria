<?php
class Avis {
    public static function ajouter($pdo, $film_id, $utilisateur_id, $note, $commentaire) {
        $stmt = $pdo->prepare("
            INSERT INTO avis (film_id, utilisateur_id, note, commentaire, valide, date)
            VALUES (?, ?, ?, ?, 0, NOW())
        ");
        $stmt->execute([$film_id, $utilisateur_id, $note, $commentaire]);
    }

    public static function getPourFilm($pdo, $film_id) {
        $stmt = $pdo->prepare("
            SELECT a.*, u.prenom, u.nom
            FROM avis a
            JOIN utilisateurs u ON u.id = a.utilisateur_id
            WHERE a.film_id = ? AND a.valide = 1
            ORDER BY a.date DESC
        ");
        $stmt->execute([$film_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getNonValides($pdo) {
        $stmt = $pdo->query("
            SELECT a.*, u.prenom, u.nom, f.titre
            FROM avis a
            JOIN utilisateurs u ON u.id = a.utilisateur_id
            JOIN films f ON f.id = a.film_id
            WHERE a.valide = 0
            ORDER BY a.date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function valider($pdo, $id) {
        $stmt = $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function supprimer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->execute([$id]);
    }
    public static function getValides($pdo) {
        $stmt = $pdo->query("
            SELECT a.*, u.prenom, u.nom, f.titre
            FROM avis a
            JOIN utilisateurs u ON u.id = a.utilisateur_id
            JOIN films f ON f.id = a.film_id
            WHERE a.valide = 1
            ORDER BY a.date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
