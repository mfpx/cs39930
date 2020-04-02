<?php

require 'config.php';
session_start(); //Initialises the session
/*
 * This block makes sure errors are
 * displayed during development
 * and that no error are displayed
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