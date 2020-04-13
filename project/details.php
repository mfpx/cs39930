<?php

session_start();

require 'system/database.php';
require 'system/core.php';

//If email and password were submitted
if (isset($_POST['function']) && $_POST['function'] == "login") {
    //Decoding base64 and sanitising email and password for special characters
    $email = base64_decode(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password_in = base64_decode(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    try {
        $st = $connection->prepare('SELECT email, password FROM users WHERE email = :email');
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
        $password_db = $row['password'];

        if (!empty($row['email'])) {
            $_SESSION['uid'] = $email; //Set a session variable 'uid' to email
            $_SESSION['doss'] = date("d/m/Y"); //DOSS - Date of session start
            $_SESSION['toss'] = date("h:i:sa"); //TOSS - Time of session start
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $connection = null;

    login($password_in, $password_db);
}

function login($password, $hash) {
    if (password_verify($password, $hash)) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['function']) && $_POST['function'] == "logout") {
    logout("index.php", 301);
}

if (isset($_POST['function']) && $_POST['function'] == "save") {
    foreach ($_POST as $key => $value) {
        if (!empty($value)) {
            $profile_edit_arr[$key] = $value;
        }
    }
    echo 0;
}