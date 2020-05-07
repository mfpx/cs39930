<?php

//Check if session exists, start/resume one if it doesn't
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Simple CSRF protection
 * @param type $token POSTed token
 * @return boolean Whether or not two tokens are the same
 */
function csrf_verify($token){
    if(hash_equals($_SESSION['token'], $token)){
        return true;
    } else {
        return false;
    }
}
