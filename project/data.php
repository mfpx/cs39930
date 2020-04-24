<?php

/*
 * Simple JSON-based GET pseudo-API
 */

//Requires database and system configuration files
require 'system/database.php';
require 'system/config.php';

//Expects an API key
$api_key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
//Expects a record ID
$img_name = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
//Optional: 1 if request is an image
$img_request = filter_input(INPUT_GET, 'image', FILTER_SANITIZE_NUMBER_INT);

//Selects an API key from the database
try {
    //PDO prepared statement with embedded SQL
    $st = $connection->prepare('SELECT api_key, disabled FROM users WHERE api_key = :key');
    //Binds a key value to SQL, and sets it to be a STRing
    $st->bindValue(':key', $api_key, PDO::PARAM_STR);
    //Executes the prepared statement
    $st->execute();

    //Get information and store it in an associative array
    $api_row = $st->fetch(PDO::FETCH_ASSOC);
    //Catches an exception if one is thrown
} catch (PDOException $e) {
    //Produces an error if the query was unsuccessful and system is in development mode
    if ($config['development']) {
        echo "Error: " . $e->getMessage();
    }
}

//Tells the user their account is disabled and kills the script
if($api_row['disabled']){
    echo 'Account disabled!';
    die();
}

//If API key matches the provided key and provided value and image name aren't empty
if ($api_key === $api_row['api_key'] && !empty($api_key) && !empty($img_name)) {
    try {
        //Select everything about the record
        $st = $connection->prepare('SELECT * FROM records WHERE img_name = :img_name');
        $st->bindValue(':img_name', $img_name, PDO::PARAM_STR);
        $st->execute();

        $data_row = $st->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if ($config['development']) {
            echo "Error: " . $e->getMessage();
        }
    }

    //If the request is an image, rather than data, and image name isn't empty
    if ($img_request === '1' && !empty($data_row['img_name'])) {
        try {
            //Select everything about the image
            $st = $connection->prepare('SELECT * FROM images WHERE img_name = :img_name');
            $st->bindValue(':img_name', $img_name, PDO::PARAM_STR);
            $st->execute();

            $img_row = $st->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($config['development']) {
                echo "Error: " . $e->getMessage();
            }
        }

        //Sets website header to the MIME type retrieved from the database
        header('Content-type: ' . $img_row['mime']);
        //Echoes image information from the database
        echo $img_row['image'];
    } else {
        if (!empty($data_row['img_name'])) {
            //Echoes JSON-encoded array information
            echo json_encode($data_row);
        } else {
            echo 'Record not found!';
        }
    }
} else {
    echo 'Not allowed!<br>';
}


//Informative messages to help user diagnose basic mistakes
if (empty($img_name) && !empty($api_key)) {
    echo 'Record ID missing!';
}

if (empty($api_key) && !empty($img_name)) {
    echo 'Key missing!';
}

if (empty($img_name) && empty($api_key)) {
    echo 'No information supplied!';
}

//Sets database connection to null (i.e. disconnects)
$connection = null;

