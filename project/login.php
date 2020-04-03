<?php
session_start();

$_SESSION['uid'] = $_POST['email'];

echo true;