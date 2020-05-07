<?php

require 'system/core.php';

if (!isset($_SESSION['uid'])) {
    include 'system/elements/heading.php';
} else {
    include 'system/elements/loggedin_heading.php';
}
include 'system/elements/pages/contact_body.php';
include 'system/elements/footer.php';
