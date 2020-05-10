<?php

session_start();

require 'system/database.php';
include 'system/mail.php';
require 'system/security.php';

//If email and function were submitted
if (isset($_POST['function']) && $_POST['function'] == "reset_req" && isset($_POST['email']) && csrf_verify($_POST['token'])) {
    //Sanitising email and password for special characters
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    try {
        $st = $connection->prepare('SELECT email, first_name, last_name FROM users WHERE email = :email');
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if ($config['development']) {
            echo "Error: " . $e->getMessage();
        } else {
            echo 0;
            die();
        }
    }

    try {
        $pr_st = $connection->prepare('SELECT valid, reset_key FROM pass_resets WHERE email = :email AND valid = TRUE');
        $pr_st->bindValue(':email', $email, PDO::PARAM_STR);
        $pr_st->execute();

        $reset_row = $pr_st->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if ($config['development']) {
            echo "Error: " . $e->getMessage();
        } else {
            echo 0;
            die();
        }
    }

    if (!empty($row['email'])) {
        if ($reset_row['valid'] == 1) {
            try {
                $st_valid = $connection->prepare('UPDATE pass_resets SET valid = 0 WHERE email = :email');
                $st_valid->bindValue(':email', $email, PDO::PARAM_STR);
                $st_valid->execute();
            } catch (PDOException $e) {
                if ($config['development']) {
                    echo "Error: " . $e->getMessage();
                } else {
                    echo 0;
                    die();
                }
            }
        }

        $date = date("Y/m/d");
        $time = date("H:i:s");
        $rkey = token(15);

        try {
            $st_reset = $connection->prepare('INSERT INTO pass_resets (email, date, time, reset_key, valid) VALUES (:email, :date, :time, :rkey, TRUE)');
            $st_reset->bindValue(':email', $email, PDO::PARAM_STR);
            $st_reset->bindValue(':date', $date, PDO::PARAM_STR);
            $st_reset->bindValue(':time', $time, PDO::PARAM_STR);
            $st_reset->bindValue(':rkey', $rkey, PDO::PARAM_STR);

            if ($st_reset->execute()) {
                if (reset_mail($email, $rkey, $row['first_name'], $row['last_name'])) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } catch (PDOException $e) {
            if ($config['development']) {
                echo "Error: " . $e->getMessage();
            } else {
                echo 0;
                die();
            }
        }
    } else {
        /*
         * Even if the email doesn't exist
         * tell the user that the query
         * might have worked
         */
        echo 1;
    }

    $connection = null;
}

if (isset($_POST['function']) && $_POST['function'] == "reset" && isset($_POST['key'])) {
    $rkey = filter_input(INPUT_POST, 'key', FILTER_SANITIZE_STRING);
    $pass_new = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $pass_repeat = filter_input(INPUT_POST, 'password_repeat', FILTER_SANITIZE_STRING);

    try {
        $st = $connection->prepare('SELECT valid, email FROM pass_resets WHERE reset_key = :rkey');
        $st->bindValue(':rkey', $rkey, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if ($config['development']) {
            echo "Error: " . $e->getMessage();
        } else {
            echo 0;
            die();
        }
    }

    if (!empty($row['valid']) && $row['valid'] == true) {
        if (hash_equals($pass_new,$pass_repeat)) {
            $pass = password_hash($pass_new, PASSWORD_DEFAULT);
            $connection->beginTransaction();

            try {
                $st_pass = $connection->prepare('UPDATE users SET password = :pass WHERE email = :email');
                $st_pass->bindValue(':email', $row['email'], PDO::PARAM_STR);
                $st_pass->bindValue(':pass', $pass, PDO::PARAM_STR);
                $st_pass->execute();

                $st_valid_update = $connection->prepare('UPDATE pass_resets SET valid = 0 WHERE reset_key = :rkey');
                $st_valid_update->bindValue(':rkey', $rkey, PDO::PARAM_STR);
                $st_valid_update->execute();

                if ($connection->commit()) {
                    echo 1;
                } else {
                    echo 0;
                }
            } catch (PDOException $e) {
                $connection->rollBack();
                if ($config['development']) {
                    echo "Error: " . $e->getMessage();
                } else {
                    echo 0;
                }
            }
        } else {
            echo -1;
        }
    } else {
        echo 0;
    }
}

function token($length) {
    return bin2hex(random_bytes($length));
}
