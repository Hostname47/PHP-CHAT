<?php

namespace classes;

class Session {

    // Check if there's a session variable with a specific name. ex: token.
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function get($name) {
        if(self::exists($name)) {
            return $_SESSION[$name];
        }

        return null;
    }

    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }
}