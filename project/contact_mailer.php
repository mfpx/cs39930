<?php

include 'system/mail.php'; //Mailer

//Filter user-accesible inputs
$function = filter_input(INPUT_POST, 'function', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$msg = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

//Integration code taken from: https://stevencotterill.com/articles/adding-google-recaptcha-v3-to-a-php-form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Ld0OPEUAAAAAD7NsHbnTnWFxGGUr1HFAneOuSnn'; //reCaptcha Secret key!
    $recaptcha_response = filter_input(INPUT_POST, 'recaptcha_response', FILTER_SANITIZE_STRING);

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    //If reCaptcha reports less than 0.5 certainty, treat that as a failed verification
    if ($recaptcha->score >= 0.5) {
        //If function is "msg", message body and email aren't empty
        if ($function === "msg" && !empty($msg) && !empty($email)) {
            //If name is empty, we will call them "a person"
            if (empty($name)) {
                $name = "a person";
            }
            //Finally attempt to send an email
            if (send_mail($email, 'Contact Us email', $msg, $name)) {
                //If successful, echo 1 back to AJAX
                echo 1;
            } else {
                //Otherwise echo 0 back to AJAX
                echo 0;
            }
        }
    } else {
        //If verification fails, report it back to AJAX
        echo -1;
    }
}