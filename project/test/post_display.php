<?php
require 'system/core.php';

echo 'set language: ' .  $_SESSION['language'] . '<br />';
echo 'username: ' . $_POST['username'] . '<br />';
echo 'password: ' . $_POST['password'];
