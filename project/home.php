<?php

require 'system/core.php';
include 'system/functions.php';

if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])){
    echo base64_decode($_SESSION['uid']);
} else {
    redirect('index.php', 301);
}
?>