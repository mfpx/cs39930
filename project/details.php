<?php

session_start();

require 'system/database.php';
require 'system/core.php';
require 'system/security.php';

if (isset($_POST['function']) && $_POST['function'] == "save" && csrf_verify($_POST['token'])) {
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

if (isset($_POST['function']) && $_POST['function'] == "new_user" && csrf_verify($_POST['token'])) {
    foreach ($_POST as $key => $value) {
        if (!empty($value)) {
            if ($key === "email") {
                filter_var($value, FILTER_SANITIZE_EMAIL);
                $profile_create_arr[$key] = $value;
            } else {
                filter_var($value, FILTER_SANITIZE_STRING);
                $profile_create_arr[$key] = $value;
            }
        }
    }

    try {
        $st_chk = $connection->prepare('SELECT email FROM users WHERE email = :email');
        $st_chk->bindValue(':email', $profile_create_arr['email'], PDO::PARAM_STR);
        $st_chk->execute();

        $chk_arr = $st_chk->fetch(PDO::FETCH_ASSOC);

        if ($chk_arr['email'] == $profile_create_arr['email']) {
            $exists = true;
        } else {
            $exists = false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    if (!$exists) {
        try {

            $connection->beginTransaction();

            $password = password_hash($profile_create_arr['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (email, first_name, last_name, admin, password) VALUES (:email, :first_name, :last_name, :admin, :password)';
            $st = $connection->prepare($sql);
            $st->bindValue(':admin', $profile_create_arr['admin'], PDO::PARAM_INT);
            $st->bindValue(':email', $profile_create_arr['email'], PDO::PARAM_STR);
            $st->bindValue(':first_name', $profile_create_arr['first_name'], PDO::PARAM_STR);
            $st->bindValue(':last_name', $profile_create_arr['last_name'], PDO::PARAM_STR);
            $st->bindValue(':password', $password, PDO::PARAM_STR);
            $st->execute();

            if ($profile_create_arr['apikey'] == 1) {
                $key = token(15);
                $st_api = $connection->prepare('UPDATE users SET api_key = :key WHERE email = :email');
                $st_api->bindValue(':email', $profile_create_arr['email'], PDO::PARAM_STR);
                $st_api->bindValue(':key', $key, PDO::PARAM_STR);
                $st_api->execute();
            }

            if (count($profile_create_arr) > 1) {
                if ($connection->commit()) {
                    echo 1;
                }
            } else {
                echo 0;
            }
        } catch (PDOException $e) {
            $connection->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo -1;
    }
}

if (isset($_POST['function']) && $_POST['function'] == "admin_edit" && csrf_verify($_POST['token'])) {
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

    try {

        $connection->beginTransaction();

        if (!empty($profile_edit_arr['first_name'])) {
            $st_fn = $connection->prepare('UPDATE users SET first_name = :fn WHERE email = :email');
            $st_fn->bindValue(':fn', $profile_edit_arr['first_name'], PDO::PARAM_STR);
            $st_fn->bindValue(':email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_fn->execute();
        }

        if (!empty($profile_edit_arr['last_name'])) {
            $st_ln = $connection->prepare('UPDATE users SET last_name = :ln WHERE email = :email');
            $st_ln->bindValue(':ln', $profile_edit_arr['last_name'], PDO::PARAM_STR);
            $st_ln->bindValue(':email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_ln->execute();
        }

        if (!empty($profile_edit_arr['password'])) {
            $password_new = password_hash($profile_edit_arr['password'], PASSWORD_DEFAULT);
            $st_password = $connection->prepare('UPDATE users SET password = :password WHERE email = :email');
            $st_password->bindValue(':password', $password_new, PDO::PARAM_STR);
            $st_password->bindValue(':email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_password->execute();
        }

        if (!empty($profile_edit_arr['email'])) {
            $st_email = $connection->prepare('UPDATE users SET email = :email WHERE email = :original_email');
            $st_email->bindValue(':email', $profile_edit_arr['email'], PDO::PARAM_STR);
            $st_email->bindValue(':original_email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_email->execute();
        }

        if (!empty($profile_edit_arr['disabled'])) {
            $st_disabled = $connection->prepare('UPDATE users SET disabled = :disabled WHERE email = :email');
            $st_disabled->bindValue(':disabled', $profile_edit_arr['disabled'], PDO::PARAM_INT);
            $st_disabled->bindValue(':email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_disabled->execute();
        }

        if (!empty($profile_edit_arr['admin']) && $profile_edit_arr['original_email'] != $_SESSION['uid']) {
            $st_admin = $connection->prepare('UPDATE users SET admin = :admin WHERE email = :email');
            $st_admin->bindValue(':admin', $profile_edit_arr['admin'], PDO::PARAM_INT);
            $st_admin->bindValue(':email', $profile_edit_arr['original_email'], PDO::PARAM_STR);
            $st_admin->execute();
        }

        if (count($profile_edit_arr) > 1) {
            if ($connection->commit()) {
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

if (isset($_POST['function']) && $_POST['function'] == "form_fill") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    try {
        $st = $connection->prepare('SELECT email, first_name, last_name, admin, disabled, api_key FROM users WHERE email = :email');
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();

        $result = $st->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $ex) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['function']) && $_POST['function'] == "delete_user" && csrf_verify($_POST['token'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email == $_SESSION['uid']) {
        echo -1;
        die();
    }

    try {
        $st_del = $connection->prepare('DELETE FROM users WHERE email = :email');
        $st_del->bindValue(':email', $email, PDO::PARAM_STR);
        if ($st_del->execute()) {
            echo 1;
        } else {
            echo 0;
        }
    } catch (PDOException $ex) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['function']) && $_POST['function'] == "new_key" && csrf_verify($_POST['token'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $token = token(15); //One byte is 2 characters long, so length of 15 will generate 30 characters
    try {
        $st_new_key = $connection->prepare('UPDATE users SET api_key = :token WHERE email = :email');
        $st_new_key->bindValue(':email', $email, PDO::PARAM_STR);
        $st_new_key->bindValue(':token', $token, PDO::PARAM_STR);
        if ($st_new_key->execute()) {
            echo $token;
        } else {
            echo 0;
        }
    } catch (PDOException $ex) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['function']) && $_POST['function'] == "delete_key" && csrf_verify($_POST['token'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    try {
        $st_del_key = $connection->prepare('UPDATE users SET api_key = NULL WHERE email = :email');
        $st_del_key->bindValue(':email', $email, PDO::PARAM_STR);
        if ($st_del_key->execute()) {
            echo 1;
        } else {
            echo 0;
        }
    } catch (PDOException $ex) {
        echo "Error: " . $e->getMessage();
    }
}

function password_match($pass, $pass_repeat) {
    if ($pass !== $pass_repeat) {
        echo -2;
        die();
    }
}
