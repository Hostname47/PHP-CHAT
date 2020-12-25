<?php

namespace classes;

class Hash {
    
    /* 
    This function will make a hash by accepting a string as parameter and salt to add it to improve the security of
    password hash because it will add randomly generated secured string of data into the end of a password
    What will happen is we give a string to make function add salt to it, hash it, so when the user try to connect again we
    take his password add salt to it again and hash that and check if it matches the aditional existing hash
    */
    public static function make($string, $salt = '') {
        return hash("sha256", $string . $salt);
    }

    // This one will add salt hhh
    public static function salt($length) {
        return bin2hex(random_bytes($length));
    }

    public static function unique() {
        return self::make(uniqid());
    }
    
}