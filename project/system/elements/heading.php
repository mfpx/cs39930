<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>
        <link rel="stylesheet" href="css/pages.css">
        <title><?php echo $title; ?></title>
        <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript">
            function do_login()
            {
                var email = $("#email").val();
                var pass = $("#password").val();
                if (email !== "" && pass !== "")
                {
                    $("#loading").css({"display": "block"});
                    $.ajax
                            ({
                                type: 'post',
                                url: 'login.php',
                                data: {
                                    do_login: "do_login",
                                    email: email,
                                    password: pass
                                },
                                success: function (response) {
                                    alert (response);
                                    if (response === '1')
                                    {
                                        window.location.href = "index.php";
                                        alert("success!");
                                    } else
                                    {
                                        $("#loading").css({"display": "none"});
                                        alert("Wrong Details");
                                    }
                                }
                            });
                } else {
                    alert("Please Fill All The Details");
                }
                return false;
            }
        </script>

    </head>
    <body>
        <div id="heading">
            <a href="index">
                <?php echo $title; ?>
            </a>
        </div>
        <hr>
        <div id="menu">
            <a href="index"><?php echo $home; ?></a>
            <a href="test">Test page</a>
            <a href="test">Test page 2</a>
            <a href="test">Test page 3</a>
            <a href="test">Test page 4</a>
        </div>
    </body>
</html>