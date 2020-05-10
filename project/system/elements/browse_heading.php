<?php
require './system/database.php';

$email = $_SESSION['uid'];

try {
    $st = $connection->prepare('SELECT admin FROM users WHERE email = :email');
    $st->bindValue(':email', $email, PDO::PARAM_STR);
    $st->execute();

    $row = $st->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$connection = null;
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>  
        <link rel="stylesheet" href="css/pages.css" />
        <link rel="shortcut icon" href="media/favicon.ico" /> 
        <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="js/notify.js"></script>
        <script type="text/javascript" src="js/logout.js"></script>
        <script type="text/javascript" src="//openlayers.org/api/OpenLayers.js"></script>
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div id="heading">
            <a href="index.php"><?php echo $title; ?></a>
        </div>
        <hr class="override">
        <div id="menu">
            <a class="right_button" onclick="return logout();"><?php echo $logout_button; ?></a>
            <a href="home.php"><?php echo $home; ?></a>
            <a href="contact.php"><?php echo $contact_button; ?></a>
            <?php
            if ($row['admin']) {
                echo "<div class=\"right_button\"><a href=\"admin.php\">$admin_button</a></div>";
            }
            ?>
        </div>
