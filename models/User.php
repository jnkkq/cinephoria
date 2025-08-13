<?php
class User {
    public static function verifierConnexion($pdo, $identifiant, $motDePasse) {
        $stmt = $pdo->prepare("
            SELECT * FROM utilisateurs
            WHERE email = :identifiant OR username = :identifiant
        ");
        $stmt->execute(['identifiant' => $identifiant]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            return $user;
        }

        return false;
    }

    public static function creer($pdo, $data) {
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (email, mot_de_passe, nom, prenom, username, role, confirme)
            VALUES (:email, :mot_de_passe, :nom, :prenom, :username, 'utilisateur', TRUE)
        ");

        $stmt->execute([
            'email' => $data['email'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'username' => $data['username']
        ]);
    }
}
?>
