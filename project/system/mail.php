<?php
function reset_mail($email, $token, $fn, $ln) {
    include 'config.php';
    
    $sender = $config['sender'];
    $subject = "Password reset";
    $message = "Dear $fn $ln,\nYour password reset link is: https://mmp-dec21.dcs.aber.ac.uk/project/project/reset_request.php?key=$token";
    $headers = 'From:' . $sender;

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
