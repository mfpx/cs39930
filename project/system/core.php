<?php

require 'config.php';
require 'database.php';
session_start(); //Initialises the session
/*
 * This block makes sure errors are
 * displayed during development
 * and that no errors are displayed
 * when the system is live
 */
if ($config['development']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

/*
 * SSL and general security procedures
 */
if ($config['level'] !== 0) {
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        if ($config['level'] === 2) {
            redirect('index.php', 301);
        } else if ($config['level'] === 1) {
            $flags['secure'] = 0;
        }
    } else if ($config['level'] === -1) {
        redirect('index.php', 301);
    } else {
        $flags['secure'] = -1;
    }
}

/*
 * Language selection
 */
if (!empty($config['language'])) {
    $lang_file = 'system/lang/' . $config['language'] . '.php'; //Init a variable with the language file path
    clearstatcache($lang_file); //Clears stat function cache for the language file

    /*
     * Initialise the language variable as null
     * This is temporary and will be overwritten later
     * Quick workaround to prevent unknown index warning
     */
    $_SESSION['language'] = NULL;

    if ($_SESSION['language'] !== $config['language'] && file_exists($lang_file)) {
        $_SESSION['language'] = $config['language']; //Set user session language to the site default
        include $lang_file; //Include the appropriate language file
    } else if ($_SESSION['language'] === $config['language'] && file_exists($lang_file)) {
        include $lang_file; //Include the appropriate language file
    } else {
        $_SESSION['language'] = "en"; //Sets session language to English in the superglobal
        include 'system/lang/' . $_SESSION['language'] . '.php'; //Fallback language - English
    }
} else {
    //If the language selection is empty
    $_SESSION['language'] = "en"; //Sets session language to English in the superglobal
    include 'system/lang/' . $_SESSION['language'] . '.php'; //Fallback language - English
}

/**
 * Destroys the session and invalidates the session cookie
 * @param String $url Location to redirect to
 * @param Integer $statusCode Status code to ues when redirecting
 */
function logout($url, $statusCode) {
    $cookie = filter_input(INPUT_COOKIE, 'PHPSESSID', FILTER_SANITIZE_STRING); //Filter cookie to make sure it's safe

    session_destroy(); //Destroys the session 
    if (isset($cookie)) { //Checks if PHPSESSID cookie is set
        setcookie('PHPSESSID', null, -1, '/'); //Invalidates PHPSESSID cookie
    }
    redirect($url, $statusCode); //Generic redirect using parameters provided
}

/**
 * Redirects user
 * @param String $location Location to redirect to
 * @param Integer $code Status code to use when redirecting
 * */
function redirect($location, $code) {
    header("Location: $location", true, $code); //Sets location header and redirect code to ones provided
    exit(); //Kills the rest of the script, just in case
}

/**
 * Checks if the user is logged in
 * @param Integer $action Specifies the action to be taken when application is called
 */
function is_loggedin($action) {

    is_disabled();
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = token(24);
    }

    if ($action === 1) {
        //If email doesn't exist in superglobal, and is empty
        if (!isset($_SESSION['uid']) && empty($_SESSION['uid'])) {
            //Redirect to index.php
            redirect('index.php', 301);
        }
    } else if ($action === 2) {
        //If email exists in superglobal, and isn't empty
        if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
            //Redirect to home.php
            redirect('home.php', 301);
        }
    }
}

function is_disabled() {
    include 'database.php';

    if (isset($_SESSION['uid'])) {
        try {
            $st_disabled = $connection->prepare('SELECT disabled FROM users WHERE email = :email');
            $st_disabled->bindValue(':email', $_SESSION['uid'], PDO::PARAM_STR);
            $st_disabled->execute();

            $row = $st_disabled->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        $disabled = $row['disabled'];

        if ($disabled && !empty($disabled)) {
            logout('index.php', 301);
        }
    }
}

function is_admin($email) {
    include 'database.php';

    try {
        $st = $connection->prepare('SELECT admin FROM users WHERE email = :email');
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();

        $row = $st->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if ($config['development']) {
            echo "Error: " . $e->getMessage();
        }
    }

    $connection = null;

    return $row['admin'];
}

function token($length) {
    return bin2hex(random_bytes($length));
}
