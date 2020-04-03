<?php

function redirect($location,$code) {
    header("Location: $location", true, $code);
    exit();
}
