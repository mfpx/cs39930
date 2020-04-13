<?php
is_loggedin(2);
?>
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
    function do_login()
    {
        var email_plain = $("#email").val();
        var pass_plain = $("#password").val();

        /*
         * Adds a layer of obfuscation when sending data
         * by encoding the data using base64
         * Might also help with certain character encoding
         * THIS IS NOT ENCRYPTION!
         */
        var pass = btoa(pass_plain);
        var email = btoa(email_plain);

        if (email !== "" && pass !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'details.php',
                        data: {
                            function: "login",
                            email: email,
                            password: pass
                        },
                        success: function (response) {
                            if (response === '1')
                            {
                                window.location.href = "home.php";
                            } else
                            {
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
             * This shouldnt be invoked
             * as the form doesnt allow
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