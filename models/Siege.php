<?php
class Siege {
    public static function getBySalle($pdo, $salleId) {
        $stmt = $pdo->prepare("SELECT * FROM sieges WHERE salle_id = ?");
        $stmt->execute([$salleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
