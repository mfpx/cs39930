<?php

require '../system/core.php';

if ($config['development'] == false){
    echo 'Production environment! <br /> Test suite disabled! <br /><br /> Please delete this folder for security reasons!';
    die();
}

echo 'hello! :)';