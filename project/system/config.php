<?php
/*
 * Language selection
 * To create a new language, create a new file with the code of that language using English as a template.
 * E.g.: for Welsh, you would create a file called "cy.php" and then change the setting below to "cy".
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
 * Database configuration
 */
$db_config['server'] = "localhost"; //Server address
$db_config['username'] = "cs39930_service"; //Username
$db_config['password'] = "H3s9EjveUWrxRfal"; //Password
$db_config['database'] = "cs39930"; //Database name
$db_config['port'] = "3306"; //Default: 3306, only change if your server runs on a non-standard port
