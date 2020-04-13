<?php

include 'system/config.php';

if ($config['level'] !== -1 && $config['level'] !== '2') {
    require 'system/core.php';

    //Body includes
    include 'system/elements/heading.php';
    include 'system/elements/pages/index_body.php';
    include 'system/elements/footer.php';
} else {
    //Load the insecure template
    include 'system/elements/' . $config['insecure_page'];
}