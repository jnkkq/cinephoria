<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])) {
    header('Location: index.php?page=accueil');
    exit;
}

require 'views/admin/index.php';
?>
