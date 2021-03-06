<?php

/*
 * Language selection
 * To create a new language, create a new file with the code of that language using English as a template.
 * E.g.: for Welsh, you would create a file called "cy.php" and then change the setting below to "cy".
 * NOTE: If you specify a broken language file, the website will default to English!
 *       To change this, edit 'core.php'.
 */
$config['language'] = "en";

/*
 * Website URL
 * Has to include the trailing slash (/)
 */
$config['url'] = "https://mmp-dec21.dcs.aber.ac.uk/project/project/";

/*
 * Sender email
 * This will be used when sending emails
 */
$config['sender'] = "noreply@aber.ac.uk";

/*
 * Contact email
 * This is the email where all messages from
 * the "Contact Us" page will be sent to
 */
$config['contact_email'] = "dec21@aber.ac.uk";

/*
 * Is the system in development mode?
 * This will show all errors, and system notifications.
 */
$config['development'] = true;

/*
 * Should the footer be displayed?
 */
$config['footer'] = false;

/*
 * SSL Security level
 * -1 - Redirect all traffic regardless of SSL security settings - DEVELOPMENT OPTION
 * 0 - Nothing will happen, regardless of whether or not SSL is enabled
 * 1 - If SSL is not enabled, it will generate a notification in the admin/users panel
 * 2 - If SSL is not enabled, it will strictly redirect all traffic to a selected page
 * 
 * NOTE: If the server is configured to automatically redirect all traffic to HTTPS
 * options 1 and 2 will have no effect!
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
