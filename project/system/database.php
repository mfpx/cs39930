<?php

require 'config.php';

//PDO declarations
$server = $db_config['server'];
$database = $db_config['database'];

//Database connection
try {
    //Creates a new database instance
    $connection = new PDO("mysql:host=$server; dbname=$database", $db_config['username'], $db_config['password']);
    //Sets error mode to exceptions
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return 1;
} catch (PDOException $e) {
    if ($config['development']) {
        //Returns the error message for debugging
        return $e->getMessage();
    }
}
