<?php
require 'config.php';

//PDO declarations
$server = $db_config['server'];
$database = $db_config['database'];

//Database connection
try {
    $connection = new PDO("mysql:host=$server; dbname=$database", $db_config['username'], $db_config['password']);
    $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Sets error mode
    return 1;
} catch (PDOException $e) {
    return $e->getMessage(); //Returns the error message for debugging
}
