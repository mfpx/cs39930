<?php

session_start();

require 'system/database.php';
require 'system/core.php';

//If email and password were submitted
if (isset($_POST['function']) && $_POST['function'] == "login") {
    //Decoding base64 and sanitising email and password for special characters
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password_in = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

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
            if ($key === "email") {
                filter_var($value, FILTER_SANITIZE_EMAIL);
                $profile_edit_arr[$key] = $value;
            } else {
                filter_var($value, FILTER_SANITIZE_STRING);
                $profile_edit_arr[$key] = $value;
            }
        }
    }
    if (!empty($profile_edit_arr['password']) && !empty($profile_edit_arr['password_repeat'])) {
        password_match($profile_edit_arr['password'], $profile_edit_arr['password_repeat']);
    }

    try {
        $st = $connection->prepare('UPDATE users SET email = :email, first_name = :fn, last_name = :ln, password = :password WHERE email = :uid');

        if (!empty($profile_edit_arr['email'])) {
            $st->bindValue(':email', $profile_edit_arr['email'], PDO::PARAM_STR);
        }
        if (!empty($profile_edit_arr['first_name'])) {
            $st->bindValue(':fn', $profile_edit_arr['first_name'], PDO::PARAM_STR);
        }
        if (!empty($profile_edit_arr['last_name'])) {
            $st->bindValue(':ln', $profile_edit_arr['last_name'], PDO::PARAM_STR);
        }
        if (!empty($profile_edit_arr['password']) && !empty($profile_edit_arr['password_repeat'])) {
            $password_new = password_hash($profile_edit_arr['password'], PASSWORD_DEFAULT);
            $st->bindValue(':password', $password_new, PDO::PARAM_STR);
        }
        if ($_SESSION['uid'] !== $profile_edit_arr['email']) {
            $st->bindValue(':uid', $_SESSION['uid'], PDO::PARAM_STR);
        }
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
        $password_db = $row['password'];

        if ($_SESSION['uid'] !== $row['email']) {
            $_SESSION['uid'] = $row['email'];
        }

        echo 1;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function password_match($pass, $pass_repeat) {
    if ($pass !== $pass_repeat) {
        echo -2;
        die();
    }
}
