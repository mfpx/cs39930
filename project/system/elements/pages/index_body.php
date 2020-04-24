<?php
is_loggedin(2);
?>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
    function do_login()
    {
        var email = $("#email").val();
        var pass = $("#password").val();

        if (email !== "" && pass !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'login.php',
                        data: {
                            function: "login",
                            email: email,
                            password: pass
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
</script>
<div id="login-box">
    <form method="post" onsubmit="return do_login();">
        <div id="login">
            <?php echo $login_text; ?>
        </div>
        <?php echo $email_field; ?>:<br />
        <input type="email" name="email" class="field" id="email" placeholder="name@example.com" required><br />
        <?php echo $password_field; ?>:<br />
        <input type="password" name="password" class="field" id="password" placeholder="••••••••" required><br />
        <input type="submit" name="login" value="<?php echo $submit_button; ?>" id="login_button">
    </form>
    <br />
    <a href="reset"><?php echo $forgotten_password; ?></a>
</div>