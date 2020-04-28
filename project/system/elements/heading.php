<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>
        <link rel="stylesheet" href="css/pages.css">
        <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="js/notify.js"></script>
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div id="heading">
            <a href="index.php"><?php echo $title; ?></a>
        </div>
        <hr class="override">
        <div id="menu">
            <a href="index.php"><?php echo $home; ?></a>
            <a href="test.php">Test page</a>
            <a href="test.php">Test page 2</a>
            <a href="test.php">Test page 3</a>
            <a href="test.php">Test page 4</a>
        </div>
