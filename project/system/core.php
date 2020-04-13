<?php

require 'config.php';
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

    if (!isset($_SESSION['language']) && file_exists($lang_file)) {
        $_SESSION['language'] = $config['language']; //Set user session language to the site default
        include $lang_file; //Include the appropriate language file
    } else {
        $_SESSION['language'] = "en"; //Sets session language to English in the superglobal
        include 'lang/en.php'; //Fallback language - English
    }
}

/**
 * Destroys the session and invalidates the session cookie
 * @param String $url Location to redirect to
 * @param Integer $statusCode Status code to ues when redirecting
 */
function logout($url, $statusCode) {
    session_destroy(); //Destroys the session 
    if (isset($_COOKIE['PHPSESSID'])) { //Checks if PHPSESSID cookie is set
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

/*
 * Checks if the user is logged in
 * i.e. if the email exists in $_SESSION superglobal
 */

function is_loggedin($action) {
    if ($action === 1) {
        if (!isset($_SESSION['uid']) && empty($_SESSION['uid'])) {
            redirect('index.php', 301);
        }
    } else if ($action === 2) {
        if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
            redirect('home.php', 301);
        }
    }
}
