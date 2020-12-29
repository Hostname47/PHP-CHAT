<?php

namespace classes;

class Token {
    
    /*
    READ IMPORTANT #1

    This functino will generate a token using md5 and put it in $_SESSION global variable by using put func in session 
    class

    */
    public static function generate($type) {
        return Session::put(Config::get("session/tokens/$type"), md5(uniqid()));
    }

    /*
    This function will check if the passed token matches the token stored in $_SESSION. First we get token name from our
    config file and then use this name to get the token stored in the $_SESSION array, and we need to check if this token name
    actually exists in $_SESSION, if so, then we need also to check if it matches the user generated token. If the two conditions
    go right, then we need to delete this session from $SESSION and return true, otherwise return false
    */

    public static function check($token, $type) {
        $tokenName = Config::get("session/tokens/$type");

        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}