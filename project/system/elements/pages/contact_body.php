<script src="https://www.google.com/recaptcha/api.js?render=6Ld0OPEUAAAAAMYa4RyFOO1VPGHXNig6o-PGzEYe"></script><!-- reCaptcha Site key! -->
<script type="text/javascript">
    function contact()
    {
        var name = $("#fullname").val();
        var email = $("#email").val();
        var msg = $("#message").val();
        var response = $("#recaptchaResponse").val();

        if (email !== "" && msg !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'contact_mailer.php',
                        data: {
                            function: "msg",
                            recaptcha_response: response,
                            fullname: name,
                            email: email,
                            message: msg
                        },
                        success: function (response) {
                            //alert(response);
                            if (response === '1') {
                                $("#login-box").notify(
                                        "<?php echo $email_sent; ?>",
                                        {
                                            position: "bottom center",
                                            className: "success"
                                        });
                                setTimeout(function () {
                                    window.location.href = "index.php";
                                }, 3000); //3s redirect delay to allow the user to read the message
                            } else if (response === '0') {
                                $("#login-box").notify(
                                        "<?php echo $email_not_sent; ?>",
                                        {
                                            position: "bottom center"
                                        });
                            } else if (response === '-1') {
                                $("#login-box").notify(
                                        "<?php echo $recaptcha_failed; ?>",
                                        {
                                            position: "bottom center"
                                        });
                            } else {
                                $("#login-box").notify(
                                        "<?php echo $error_generic; ?>",
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
                    "<?php echo $fill_form; ?>",
                    {
                        position: "bottom center"
                    });
        }
        return false;
    }
</script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('6Ld0OPEUAAAAAMYa4RyFOO1VPGHXNig6o-PGzEYe', {action: 'homepage'}).then(function (token) { //reCaptcha Site key!
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>
<div id="login-box">
    <form method="post" onsubmit="return contact();">
        <div id="login">
            <?php echo $contact_heading; ?>
        </div>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <?php echo $name; ?>:<br />
        <input type="text" name="name" class="field" id="fullname" placeholder="John Smith"><br />
        <?php echo $email_field; ?>:<br />
        <input type="email" name="email" class="field" id="email" placeholder="name@example.com" required><br />
        <?php echo $message; ?>:<br />
        <textarea id="message" rows="6" cols="30" placeholder="<?php echo $enter_message; ?>" required></textarea><br />
        <input type="submit" value="<?php echo $submit_button; ?>" id="login_button">
    </form>
</div>
</body>