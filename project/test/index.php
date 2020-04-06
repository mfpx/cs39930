<?php

require '../system/core.php';

if ($config['development'] == false){
    echo 'Production environment! <br /> Test suite disabled! <br /><br /> Please delete this folder for security reasons!';
    die();
}

echo 'hello! :)';


$pass = '$2y$10$tF1So0OJO5D.FIozVGrV5.vmgntHwlrcmCjUcsPTUXn0rpm4DFRgC';
$hash = password_hash("test", PASSWORD_DEFAULT);

$valid = password_verify($pass, $hash);
var_dump($valid);

if ($valid){
    echo 'hello there';
} else {
    echo 'bye :(';
}