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

function send_mail($email, $subject, $message, $name){
    include 'config.php';
    
    $sender = $config['sender'];
    $receiver = $config['contact_email'];
    $msg = "Hello,\nEmail from: $email\n\nA message from $name:\n$message";
    $headers = 'From:' . $sender;
    
    if (mail($receiver, $subject, $msg, $headers)){
        return true;
    } else {
        return false;
    }
}
