<?php
class Employe {
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM utilisateurs WHERE role = 'employe'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($pdo, $email, $username, $mot_de_passe, $prenom, $nom) {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, username, mot_de_passe, prenom, nom, role) VALUES (?, ?, ?, ?, ?, 'employe')");
        $stmt->execute([$email, $username, $hash, $prenom, $nom]);
    }

    public static function supprimer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND role = 'employe'");
        $stmt->execute([$id]);
    }

    public static function reinitialiserMotDePasse($pdo, $id, $nouveau) {
        $hash = password_hash($nouveau, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ? AND role = 'employe'");
        $stmt->execute([$hash, $id]);
    }

    public static function modifier($pdo, $id, $email, $username, $prenom, $nom, $role) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ?, username = ?, prenom = ?, nom = ?, role = ? WHERE id = ? AND role = 'employe'");
        $stmt->execute([$email, $username, $prenom, $nom, $role, $id]);
    }
}
