<?php
require './system/database.php';

is_loggedin(1);

if (!is_admin($_SESSION['uid']) && isset($_SESSION['uid'])) {
    redirect('home.php', 301);
}

try {
    $st = $connection->prepare('SELECT * FROM users');
    $st->execute();

    $row = $st->fetchAll(PDO::FETCH_ASSOC);

    echo '
        <div id="user-left">
        <div id="user-flexbox">
        <h2>User list</h2>
        <button id="new-user">New user</button>
        </div>
        <table>
        <tr>
        <th>Email</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Admin</th>
        <th>Disabled</th>
        <th>API Key</th>
        </tr>
        ';
    foreach ($row as $result) {
        if ($result['admin'] == 1) {
            $result['admin'] = "Yes";
        } else {
            $result['admin'] = "No";
        }

        if ($result['disabled'] == 1) {
            $result['disabled'] = "Yes";
        } else {
            $result['disabled'] = "No";
        }

        if (empty($result['api_key'])) {
            $result['api_key'] = "None";
        }

        echo '
            <tr>
            <td>' . $result['email'] . '</td>
            <td>' . $result['first_name'] . '</td>
            <td>' . $result['last_name'] . '</td>
            <td>' . $result['admin'] . '</td>
            <td>' . $result['disabled'] . '</td>
            <td>' . $result['api_key'] . '</td>
            <form onsubmit="return form_fill(\'' . $result['email'] . '\')">
            <td><button>Edit</button></td>
            </form>
            </tr>
             ';
    }

    echo '</table>
          </div>';
} catch (Exception $e) {
    if ($config['development']) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#change-button-hide").click(function () {
            location.reload(false);
            $("#change-form").hide();
            $("#overlay").hide();
        });
    });

    $(document).ready(function () {
        $("#new-user").click(function () {
            $("#new-user-form").show();
            $("#overlay").show();
        });
    });

    $(document).ready(function () {
        $("#new-button-hide").click(function () {
            location.reload(false);
            $("#new-user-form").hide();
            $("#overlay").hide();
        });
    });

    $(document).ready(function () {
        $("#new-key").click(function () {
            if (confirm("Are you sure you want to generate a new API key?")) {
                var email = $("#email").val();
                var token = '<?php echo $_SESSION['token']; ?>';
                $.ajax
                        ({
                            type: 'post',
                            url: 'details.php',
                            data: {
                                function: "new_key",
                                email: email,
                                token: token
                            },
                            success: function (response) {
                                //alert(response);
                                if (response) {
                                    $("#apikey").val(response);
                                    $("#change-form").notify(
                                            "<?php echo $apikey_changed; ?>",
                                            {
                                                position: "bottom center",
                                                className: "success"
                                            });
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
        });
    });

    $(document).ready(function () {
        $("#delete-key").click(function () {
            if (confirm("Are you sure you want to remove API access?")) {
                var email = $("#email").val();
                var token = '<?php echo $_SESSION['token']; ?>';
                $.ajax
                        ({
                            type: 'post',
                            url: 'details.php',
                            data: {
                                function: "delete_key",
                                email: email,
                                token: token
                            },
                            success: function (response) {
                                //alert(response);
                                if (response === '1') {
                                    $("#apikey").val(null);
                                    $("#change-form").notify(
                                            "<?php echo $apikey_deleted; ?>",
                                            {
                                                position: "bottom center",
                                                className: "success"
                                            });
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
        });
    });


    $(document).ready(function () {
        $("#delete-user").click(function () {
            if (confirm("Are you sure you want to delete this user? This action cannot be undone!")) {
                var email = $("#email").val();
                var token = '<?php echo $_SESSION['token']; ?>';
                $.ajax
                        ({
                            type: 'post',
                            url: 'details.php',
                            data: {
                                function: "delete_user",
                                email: email,
                                token: token
                            },
                            success: function (response) {
                                //alert(response);
                                if (response === '1') {
                                    $("#email").prop('disabled', true);
                                    $("#first_name").prop('disabled', true);
                                    $("#last_name").prop('disabled', true);
                                    $("#password").prop('disabled', true);
                                    $("#disabled").prop('disabled', true);
                                    $("#admin").prop('disabled', true);
                                    $("#save-button").prop('disabled', true);

                                    $("#change-form").notify(
                                            "<?php echo $delete_success; ?>",
                                            {
                                                position: "bottom center",
                                                className: "success"
                                            });
                                    /*
                                     * Reloads the page for changes to show
                                     * This is easier than having to load every element again
                                     */
                                    setTimeout(function () {
                                        location.reload(false);
                                    }, 3000); //3s delay to allow the user to read the message
                                } else if (response === '-1') {
                                    $("#delete-user").hide();
                                    $("#change-form").notify(
                                            "<?php echo $delete_own_fail; ?>",
                                            {
                                                position: "bottom center"
                                            });
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
        });
    });

    function form_fill(email) {
        $.ajax
                ({
                    type: 'post',
                    url: 'details.php',
                    data: {
                        function: "form_fill",
                        email: email
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);
                        $("#email").val(obj.email).attr('placeholder', obj.email);
                        $("#original_email").val(obj.email);
                        $("#first_name").val(obj.first_name).attr('placeholder', obj.first_name);
                        $("#last_name").val(obj.last_name).attr('placeholder', obj.last_name);

                        if (obj.disabled === '1') {
                            $("#disabled").prop('checked', true);
                        } else {
                            $("#disabled").prop('checked', false);
                        }
                        if (obj.admin === '1') {
                            $("#admin").prop('checked', true);
                        } else {
                            $("#admin").prop('checked', false);
                        }
                        $("#apikey").val(obj.api_key);

                        $("#change-form").show();
                        $("#overlay").show();
                    }
                });
        return false;
    }

    function new_user() {

        if ($("#admin_new").prop('checked')) {
            var admin_state = 1;
        } else {
            var admin_state = 'false';
        }

        if ($("#apikey_new").prop('checked')) {
            var apikey_gen = 1;
        } else {
            var apikey_gen = 'false';
        }

        var email = $("#email_new").val();
        var first_name = $("#first_name_new").val();
        var last_name = $("#last_name_new").val();
        var password = $("#password_new").val();
        var token = '<?php echo $_SESSION['token']; ?>';

        $.ajax
                ({
                    type: 'post',
                    url: 'details.php',
                    data: {
                        function: "new_user",
                        email: email,
                        apikey: apikey_gen,
                        admin: admin_state,
                        password: password,
                        last_name: last_name,
                        first_name: first_name,
                        token: token
                    },
                    success: function (response) {
                        alert(response);
                        if (response === '1') {
                            $("#new-user-form").notify(
                                    "<?php echo $new_user_success; ?>",
                                    {
                                        position: "bottom center",
                                        className: "success"
                                    });
                            /*
                             * Reloads the page for changes to show
                             * This is easier than having to load every element again
                             */
                            setTimeout(function () {
                                location.reload(false);
                            }, 3000); //3s delay to allow the user to read the message
                        } else if (response === '-1') {
                            $("#new-user-form").notify(
                                    "<?php echo $user_exists; ?>",
                                    {
                                        position: "bottom center"
                                    });
                        } else {
                            $("#new-user-form").notify(
                                    "<?php echo $error_generic; ?>",
                                    {
                                        position: "bottom center"
                                    });
                        }
                    }
                });
        return false;
    }

    function save() {
        if ($("#disabled").prop('checked')) {
            var disabled_state = 1;
        } else {
            var disabled_state = 'false';
        }

        if ($("#admin").prop('checked')) {
            var admin_state = 1;
        } else {
            var admin_state = 'false';
        }

        //var disabled_state = $("#disabled").prop('checked');
        //var admin_state = $("#admin").prop('checked');
        var email = $("#email").val();
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var password = $("#password").val();
        var original_email = $("#original_email").val();
        var token = '<?php echo $_SESSION['token']; ?>';

        $.ajax
                ({
                    type: 'post',
                    url: 'details.php',
                    data: {
                        function: "admin_edit",
                        email: email,
                        original_email: original_email,
                        disabled: disabled_state,
                        admin: admin_state,
                        password: password,
                        last_name: last_name,
                        first_name: first_name,
                        token: token
                    },
                    success: function (response) {
                        //alert(response);
                        if (response === '1') {
                            $("#change-form").notify(
                                    "<?php echo $detail_success; ?>",
                                    {
                                        position: "bottom center",
                                        className: "success"
                                    });
                            /*
                             * Reloads the page for changes to show
                             * This is easier than having to load every element again
                             */
                            setTimeout(function () {
                                location.reload(false);
                            }, 3000); //3s delay to allow the user to read the message
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
<div id="overlay"></div>
<div id="change-form">
    <form onsubmit="return save();" method="post">
        <?php echo $email_field; ?>:<br />
        <input type="email" id="email"><br />
        <?php echo $first_name_field; ?>:<br />
        <input type="text" id="first_name"><br />
        <?php echo $last_name_field; ?>:<br />
        <input type="text" id="last_name"><br />
        <?php echo $new_password_field; ?>:<br />
        <input type="password" id="password" placeholder="••••••••"><br />
        Disabled: 
        <input type="checkbox" id="disabled"><br />
        Administrator: 
        <input type="checkbox" id="admin"><br />
        API Key:<br />
        <input type="text" id="apikey" disabled><br />
        <input type="hidden" id="original_email">
        <button id="save-button"><?php echo $save_button; ?></button>
    </form>
    <button id="change-button-hide"><?php echo $cancel_button; ?></button>
    <button id="delete-user"><?php echo $delete_user; ?></button>
    <button id="new-key"><?php echo $new_key; ?></button>
    <button id="delete-key"><?php echo $remove_key; ?></button>
</div>

<div id="new-user-form">
    <form onsubmit="return new_user();" method="post">
        <?php echo $email_field; ?>:<br />
        <input type="email" id="email_new" placeholder="email@example.com" required><br />
        <?php echo $first_name_field; ?>:<br />
        <input type="text" id="first_name_new" placeholder="John" required><br />
        <?php echo $last_name_field; ?>:<br />
        <input type="text" id="last_name_new" placeholder="Smith" required><br />
        <?php echo $new_password_field; ?>:<br />
        <input type="password" id="password_new" placeholder="••••••••" required><br />
        Administrator: 
        <input type="checkbox" id="admin_new"><br />
        API Access:
        <input type="checkbox" id="apikey_new"><br />
        <button id="save-button"><?php echo $save_button; ?></button>
    </form>
    <button id="new-button-hide"><?php echo $cancel_button; ?></button>
</div>
</body>