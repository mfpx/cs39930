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
                        $.notify("<?php echo $logout_fail; ?>"); //This shouldn't happen!
                    }
                }
            });
    return false;
}