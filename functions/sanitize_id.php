<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

function sanitize_id($id) {
    if(is_numeric($id) || $id === 0) {
        return $id;
    } else {
        false;
    }
}