<?php
is_loggedin(2);

try {
    $rkey = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);

    $st = $connection->prepare('SELECT valid FROM pass_resets WHERE reset_key = :rkey');
    $st->bindValue(':rkey', $rkey, PDO::PARAM_STR);
    $st->execute();

    $row = $st->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    if ($config['development']) {
        echo "Error: " . $e->getMessage();
    }
}

if ($row['valid'] != 1 || empty($row['valid'])) {
    redirect('index.php', 301);
}
?>
<script type="text/javascript">
    function reset_send()
    {
        var rkey = $("#key").val();
        var password = $("#password").val();
        var password_repeat = $("#password_repeat").val();

        if (password !== "" && password_repeat !== "")
        {
            $.ajax
                    ({
                        type: 'post',
                        url: 'reset.php',
                        data: {
                            function: "reset",
                            key: rkey,
                            password: password,
                            password_repeat: password_repeat
                        },
                        success: function (response) {
                            alert(response);
                            if (response === '1') {
                                $("#login-box").notify(
                                        "<?php echo $p_change_success; ?>",
                                        {
                                            position: "bottom center",
                                            className: "success"
                                        });
                                setTimeout(function () {
                                    window.location.href = "index.php";
                                }, 3000); //3s redirect delay to allow the user to read the message
                            } else if (response === '-1') {
                                $("#login-box").notify(
                                        "<?php echo $password_mismatch; ?>",
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
                    "<?php echo $enter_passwords; ?>",
                    {
                        position: "bottom center"
                    });
        }
        return false;
    }
</script>
<div id="login-box">
    <form method="post" onsubmit="return reset_send();">
        <div id="login">
            <?php echo $password_change; ?>
        </div>
        <input type="hidden" value="<?php echo filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING); ?>" id="key">
        <?php echo $new_password_field; ?>:<br />
        <input type="password" name="password class="field" id="password" placeholder="••••••••" required><br />
        <?php echo $new_password_repeat_field; ?>:<br />
        <input type="password" name="password" class="field" id="password_repeat" placeholder="••••••••" required><br />
        <input type="submit" value="<?php echo $submit_button; ?>" id="login_button">
    </form>
</div>
</body>
</html>