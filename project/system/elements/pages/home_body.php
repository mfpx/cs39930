<?php
is_loggedin(1);
require './system/database.php';

$email = $_SESSION['uid'];

try {
    $st = $connection->prepare('SELECT first_name, last_name, admin FROM users WHERE email = :email');
    $st->bindValue(':email', $email, PDO::PARAM_STR);
    $st->execute();

    $row = $st->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$connection = null;
?>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
    function logout()
    {
        $.ajax
                ({
                    type: 'post',
                    url: 'login.php',
                    data: {
                        function: "logout"
                    },
                    success: function (response) {
                        if (response) {
                            window.location.href = "index.php";
                        } else {
                            console.log("Logout failed!");
                        }
                    }
                });
        return false;
    }
    $(document).ready(function () {
        $("#change-button").click(function () {
            $("#change-form").toggle();
        });
    });
</script>
<div id="welcome">
    Welcome, <?php echo $row['first_name'] . " " . $row['last_name']; ?>
</div>
<div id="session">
    Session started: <?php echo $_SESSION['doss'] . ' ' . $_SESSION['toss']; ?>
</div>
<button id="change-button">Edit profile</button>
<div id="change-form">
    <form>
        Email:
        <input type="text" id="email">
    </form>
</div>