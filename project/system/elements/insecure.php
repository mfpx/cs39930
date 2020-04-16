<?php
/*
 * Code block taken from core.php
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
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>
        <link rel="stylesheet" href="css/pages.css">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div id="centre-warn">
            <?php echo $disabled_text; ?>
        </div>
    </body>
</html>