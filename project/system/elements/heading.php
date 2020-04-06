<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>
        <link rel="stylesheet" href="css/pages.css">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div id="heading">
            <a href="index.php">
                <?php echo $title; ?>
            </a>
        </div>
        <hr>
        <div id="menu">
            <a href="index.php"><?php echo $home; ?></a>
            <a href="test.php">Test page</a>
            <a href="test.php">Test page 2</a>
            <a href="test.php">Test page 3</a>
            <a href="test.php">Test page 4</a>
        </div>
    </body>
</html>