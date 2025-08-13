<?php
class Genre {
    public static function getAll($pdo) {
        return $pdo->query("SELECT * FROM genres")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
