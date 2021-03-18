<?php

namespace classes;

class Redirect {
    public static function to($location=null) {
        if(isset($location)) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header("HTTP/1.0 404 Not Found");
                        header("Location: " . Config::get("root/path") . "page_parts/errors/404.php");
                        exit();
                    break;
                }
            }
            header("Location: " . $location);
        }
    }
}