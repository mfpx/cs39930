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
            <a id="logout" onclick="return logout()">Logout</a>
            <a href="test.php">Test page 2</a>
            <a href="test.php">Test page 3</a>
            <a href="test.php">Test page 4</a>
            <?php
            if ($row['admin']) {
                echo "<div id=\"admin\"><a href=\"admin.php\">Admin</a></div>";
            }
            ?>
        </div>
    </body>
</html>