<?php
require 'vendor/autoload.php'; // composer require mongodb/mongodb
$client = new MongoDB\Client("mongodb://localhost:27017");
$mongo = $client->cinephoria;
