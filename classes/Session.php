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

    /*
    we need to print the message only one time, so for exemple if we register successfully, we need to see the registration 
    success message only one time, so that next time when we refresh the page we don't have to see it again.
    Flash function: will check if the session with $name name exists, if so, we store this session data var in a variable
    and then delete this session variable and return the variable that hold the data
    If the session is not there, we simply add it by using Session::put function so that we can actually fetch it again in other page like in index page when the user successfully registered
    */
    public static function flash($name, $message='') {
        if(self::exists($name)) {
            $sessionData = Session::get($name);
            Session::delete($name);
            return $sessionData;
        } else {
            Session::put($name, $message);
        }
    }
}