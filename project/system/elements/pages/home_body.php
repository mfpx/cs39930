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

try {
    $user_motd = $connection->query('SELECT user_announcement FROM system');
    $user_motd->execute();

    $result = $user_motd->fetch(PDO::FETCH_ASSOC);
    if (empty($result['user_announcement'])) {
        $announcement = $announce_blank;
    } else {
        $announcement = $result['user_announcement'];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$connection = null;
?>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript" src="js/logout.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#change-button-show").click(function () {
            $("#change-form").show();
            $("#overlay").show();
        });
    });
    $(document).ready(function () {
        $("#change-button-hide").click(function () {
            $("#change-form").hide();
            $("#overlay").hide();
        });
    });
    function save_changes() {
        var email = $("#email").val();
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var password_in = $("#password").val();
        var password_repeat = $("#password_repeat").val();
        $.ajax
                ({
                    type: 'post',
                    url: 'details.php',
                    data: {
                        function: "save",
                        email: email,
                        password: password_in,
                        password_repeat: password_repeat,
                        first_name: first_name,
                        last_name: last_name
                    },
                    success: function (response) {
                        alert(response);
                        if (response === '1') {
                            /*
                             * Realoads the page for changes to show
                             * This is easier than having to load every element again
                             */
                            location.reload(false);
                        } else if (response === '-2') { //If the script returned a "password mismatch" error
                            $("#change-form").notify(
                                    "<?php echo $password_mismatch; ?>",
                                    {
                                        position: "bottom center"
                                    });
                            /*
                             * Two lines below reset the password
                             * input fields if password doesn't match
                             */
                            $("#password").val("");
                            $("#password_repeat").val("");
                        } else {
                            $("#change-form").notify(
                                    "<?php echo $error_generic; ?>",
                                    {
                                        position: "bottom center"
                                    });
                        }
                    }
                });
        return false;
    }
</script>
<div id="welcome">
    <?php echo $welcome . ', ' . $row['first_name'] . " " . $row['last_name']; ?>
</div>
<div id="session">
    <?php echo $session_started; ?>: <?php echo $_SESSION['doss'] . ' ' . $_SESSION['toss']; ?>
</div>
<button id="change-button-show"><?php echo $edit_profile; ?></button>
<div class="motd-wrapper">
    <div class="ann-title"><?php echo $announce; ?>:</div>
    <div id="user-motd"><?php echo $announcement; ?></div>
</div>
<div id="overlay"></div>
<div id="change-form">
    <form onsubmit="return save_changes();" method="post">
        <?php echo $email_field; ?>:<br />
        <input type="email" id="email" value="<?php echo $email; ?>"><br />
        <?php echo $first_name_field; ?>:<br />
        <input type="text" id="first_name" value="<?php echo $row['first_name']; ?>"><br />
        <?php echo $last_name_field; ?>:<br />
        <input type="text" id="last_name" value="<?php echo $row['last_name']; ?>"><br />
        <?php echo $new_password_field; ?>:<br />
        <input type="password" id="password"><br />
        <?php echo $new_password_repeat_field; ?>:<br />
        <input type="password" id="password_repeat">
        <button><?php echo $save_button; ?></button>
    </form>
    <button id="change-button-hide"><?php echo $cancel_button; ?></button>
</div>