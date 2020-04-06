<?php
is_loggedin(1);
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
</script>
<button onclick="return logout()">Logout</button>