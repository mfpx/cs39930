<?php
/*
 * Language selection
 * To create a new language, create a new file with the code of that language using English as a template.
 * E.g.: for Welsh, you would create a file called "cy.php" and then change the setting below to "cy".
 * NOTE: If you specify a broken language file, the website will default to English!
 *       To change this, edit 'core.php'.
 */

$config['language'] = 'en';

/*
 * Is the system in development mode?
 * This will show all errors, and system notifications.
 */

$config['development'] = true;

/*
 * Should the footer be displayed?
 */

$config['footer'] = true;

/*
 * SSL Security level
 * -1 - Redirect all traffic regardless of SSL security settings - DEVELOPMENT OPTION
 * 0 - Nothing will happen, regardless of whether or not SSL is enabled
 * 1 - If SSL is not enabled, it will generate an error in the admin panel
 * 2 - If SSL is not enabled, it will strictly redirect all traffic to a selected page
 */
$config['level'] = 2;
$config['insecure_page'] = "insecure.php";

/*
 * Database configuration
 */
$db_config['server'] = "localhost"; //Server address
$db_config['username'] = "cs39930_service"; //Username
$db_config['password'] = "H3s9EjveUWrxRfal"; //Password
$db_config['database'] = "cs39930"; //Database name
$db_config['port'] = "3306"; //Default: 3306, only change if your server runs on a non-standard port
