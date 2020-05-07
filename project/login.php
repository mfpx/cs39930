<?php

session_start();

require 'system/database.php';
require 'system/core.php';
require 'system/security.php';

//If email and password were submitted
if (isset($_POST['function']) && $_POST['function'] == "login" && csrf_verify($_POST['token'])) {
    //Sanitising email and password for special characters
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password_in = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    try {
        $st = $connection->prepare('SELECT email, password, disabled FROM users WHERE email = :email');
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
        $password_db = $row['password'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $connection = null;

    if ($row['disabled']) {
        echo -1;
    } else {
        login($password_in, $password_db, $row['email']);
    }
}

function login($password, $hash, $email) {
    if (password_verify($password, $hash)) {

        if (!empty($email)) {
            $_SESSION['uid'] = $email; //Set a session variable 'uid' to email
            $_SESSION['doss'] = date("d/m/Y"); //DOSS - Date of session start
            $_SESSION['toss'] = date("H:i:s"); //TOSS - Time of session start
        }

        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['function']) && $_POST['function'] == "logout") {
    logout("index.php", 301);
}
