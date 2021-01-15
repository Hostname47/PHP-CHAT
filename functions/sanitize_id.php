<?php

function sanitize_id($id) {
    if(!isset($id) || empty($id)) {
        return false;
    }

    if(is_numeric($id) || $id == 0) {
        return $id;
    } else {
        false;
    }
}