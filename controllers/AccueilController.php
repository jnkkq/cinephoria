<?php
require_once 'models/Film.php';

$films = Film::getDernierMercredi($pdo);

require 'views/pages/accueil.php';
?>
