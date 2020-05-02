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
            <form onsubmit="return edit(\'' . $result['email'] . '\')">
            <td><button>Edit</button></td>
            </form>
            </tr>
             ';
    }

    echo '</table>';
} catch (Exception $e) {
    if ($config['development']) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#change-button-hide").click(function () {
            $("#change-form").hide();
            $("#overlay").hide();
        });
    });

    function edit(email) {
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
                        $("#email").val(obj.email);
                        $("#first_name").val(obj.first_name);
                        $("#last_name").val(obj.last_name);
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

    function do_delete(email) {
        alert(email);
        return false;
    }

    function save() {
        var email = $("#email").val();
        $.ajax
                ({
                    type: 'post',
                    url: 'details.php',
                    data: {
                        function: "test2",
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
<div id="overlay"></div>
<div id="change-form">
    <form onsubmit="return save();" method="post">
        <?php echo $email_field; ?>:<br />
        <input type="email" id="email"><br />
        <?php echo $first_name_field; ?>:<br />
        <input type="text" id="first_name"><br />
        <?php echo $last_name_field; ?>:<br />
        <input type="text" id="last_name"><br />
        Disabled: 
        <input type="checkbox" id="disabled"><br />
        Administrator: 
        <input type="checkbox" id="admin">
        API Key:<br />
        <input type="text" id="apikey" disabled><br />
        <button><?php echo $save_button; ?></button>
    </form>
    <button id="change-button-hide"><?php echo $cancel_button; ?></button>
    <button id="change-button-hide">New API key</button>
    <button id="change-button-hide">Remove API access</button>
</div>