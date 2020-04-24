<?php

session_start();

require 'system/database.php';
require 'system/core.php';

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

        $connection->beginTransaction();

        if (!empty($profile_edit_arr['first_name'])) {
            $st_fn = $connection->prepare('UPDATE users SET first_name = :fn WHERE email = :email2');
            $st_fn->bindValue(':fn', $profile_edit_arr['first_name'], PDO::PARAM_STR);
            $st_fn->bindValue(':email2', $_SESSION['uid'], PDO::PARAM_STR);
            $st_fn->execute();
        }

        if (!empty($profile_edit_arr['last_name'])) {
            $st_ln = $connection->prepare('UPDATE users SET last_name = :ln WHERE email = :email2');
            $st_ln->bindValue(':ln', $profile_edit_arr['last_name'], PDO::PARAM_STR);
            $st_ln->bindValue(':email2', $_SESSION['uid'], PDO::PARAM_STR);
            $st_ln->execute();
        }

        if (!empty($profile_edit_arr['password']) && !empty($profile_edit_arr['password_repeat'])) {
            $password_new = password_hash($profile_edit_arr['password'], PASSWORD_DEFAULT);
            $st_password = $connection->prepare('UPDATE users SET password = :password WHERE email = :email2');
            $st_password->bindValue(':password', $password_new, PDO::PARAM_STR);
            $st_password->bindValue(':email2', $_SESSION['uid'], PDO::PARAM_STR);
            $st_password->execute();
        }

        if (!empty($profile_edit_arr['email'])) {
            $st_email = $connection->prepare('UPDATE users SET email = :email WHERE email = :email2');
            $st_email->bindValue(':email', $profile_edit_arr['email'], PDO::PARAM_STR);
            $st_email->bindValue(':email2', $_SESSION['uid'], PDO::PARAM_STR);
            $st_email->execute();
        }

        if (!empty($profile_edit_arr['email'])) {
            if ($_SESSION['uid'] !== $profile_edit_arr['email']) {
                $email = $profile_edit_arr['email'];
            } else {
                $email = $_SESSION['uid'];
            }
        }

        if (count($profile_edit_arr) > 1) {
            if ($connection->commit()) {
                $_SESSION['uid'] = $profile_edit_arr['email'];
                echo 1;
            }
        } else {
            echo 0;
        }
    } catch (PDOException $e) {
        $connection->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

function password_match($pass, $pass_repeat) {
    if ($pass !== $pass_repeat) {
        echo -2;
        die();
    }
}
