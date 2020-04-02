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
$db_config['server'] = ""; //Server address
$db_config['username'] = ""; //Username
$db_config['password'] = ""; //Password
$db_config['database'] = ""; //Database name
$db_config['port'] = "3306"; //Default: 3306, only change if your server runs on a non-standard port
