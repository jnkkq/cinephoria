<?php
// Connexion à la base de données MySQL
$host = 'localhost';
$dbname = 'cinephoria';
$username = 'root'; // à adapter selon ton environnement
$password = '';     // à adapter aussi

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
