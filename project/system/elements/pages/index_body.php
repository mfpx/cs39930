<?php
is_loggedin(2);

try {
    $st = $connection->prepare('SELECT img_name FROM records');
    $st->execute();
    
    $count = $st->rowCount();
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#p-reset").click(function () {
            $("#reset-form").show();
            $("#overlay").show();
        });
    });

    $(document).ready(function () {
        $("#reset-form-hide").click(function () {
            $("#reset-form").hide();
            $("#overlay").hide();
        });
    });

    function do_login()
    {
        var email = $("#email").val();
        var pass = $("#password").val();
        var token = $("#token").val();

        if (email !== "" && pass !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'login.php',
                        data: {
                            function: "login",
                            email: email,
                            password: pass,
                            token: token
                        },
                        success: function (response) {
                            //alert(response);
                            if (response === '1') {
                                window.location.href = "home.php";
                            } else if (response === '-1') {
                                $("#login-box").notify(
                                        "<?php echo $account_disabled; ?>",
                                        {
                                            position: "bottom center"
                                        });
                            } else {
                                $("#login-box").notify(
                                        "<?php echo $wrong_credentials; ?>",
                                        {
                                            position: "bottom center"
                                        });
                            }
                        }
                    });
        } else {
            /*
             * This shouldn't be invoked
             * as the form doesn't allow
             * submission of blank fields
             * 
             * This is used as fallback
             */
            $("#login-box").notify(
                    "<?php echo $enter_credentials; ?>",
                    {
                        position: "bottom center"
                    });
        }
        return false;
    }

    function do_reset()
    {
        var email = $("#reset_email").val();
        var token = $("#token").val();

        if (email !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'reset.php',
                        data: {
                            function: "reset_req",
                            email: email,
                            token: token
                        },
                        success: function (response) {
                            //alert(response);
                            if (response === '1') {
                                $("#reset-form").hide();
                                $("#overlay").hide();
                                $("#login-box").notify(
                                        "If the email provided is correct, you will receive an email!",
                                        {
                                            position: "bottom center",
                                            className: "info"
                                        });
                            } else {
                                $("#reset-form").notify(
                                        "Something went wrong! Please try again later!",
                                        {
                                            position: "bottom center"
                                        });
                            }
                        }
                    });
        } else {
            /*
             * This shouldn't be invoked
             * as the form doesn't allow
             * submission of blank fields
             * 
             * This is used as fallback
             */
            $("#reset-form").notify(
                    "Please enter your email!",
                    {
                        position: "bottom center"
                    });
        }
        return false;
    }
</script>
<div id="head-text">
<?php echo $main_welcome . '<br />' .
$run_by . '. ' . $db_contains . '. ' .
$db_has . ' ' . $count . ' ' . $db_records . '.';
?>
</div>
<div id="login-box">
    <form method="post" onsubmit="return do_login();">
        <div id="login">
            <?php echo $login_text; ?>
        </div>
        <?php echo $email_field; ?>:<br />
        <input type="email" name="email" class="field" id="email" placeholder="name@example.com" required><br />
        <?php echo $password_field; ?>:<br />
        <input type="password" name="password" class="field" id="password" placeholder="••••••••" required><br />
        <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
        <input type="submit" name="login" value="<?php echo $submit_button; ?>" id="login_button">
    </form>
    <br />
    <button id="p-reset"><?php echo $forgotten_password; ?></button>
</div>
<div id="overlay"></div>
<div id="reset-form">
    <form onsubmit="return do_reset();" method="post">
        <?php echo $email_field; ?>:<br />
        <input type="email" id="reset_email" placeholder="name@example.com" required><br />
        <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
        <button><?php echo $reset_button; ?></button>
    </form>
    <button id="reset-form-hide"><?php echo $cancel_button; ?></button>
</div>
</body>


