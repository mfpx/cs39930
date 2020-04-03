<?php
include '../system/database.php';

if ($_POST) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    try {
        $st = $connection->prepare('SELECT email, first_name, last_name FROM users WHERE email = :email');
        $st -> bindValue(':email', $email, PDO::PARAM_STR);
        $st -> execute();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            echo $row['email'] . '<br />';
            echo $row['first_name'];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
}
?>
<form method="post" action="connection_test.php">
    email: <br />
    <input type="text" name="email"><br />
    password: <br />
    <input type="text" name="password"><br />
    <input type="submit">
</form>