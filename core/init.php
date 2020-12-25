<?php

// Check if session is not started, then start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$GLOBALS["config"] = array(
    "mysql" => array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'db'=>'chat'
    ),
    "remember"=> array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    "session"=>array(
        'session_name'=>'user',
        "token_name" => "token"
    ),
    "root"=> array(
        'path'=>'http://127.0.0.1/CHAT/'
    )
);

/* 
IMPORTANT : 
1 - sanitize function file could not be included here because the path will be relative to the caller script
so if for example include it like following: include_once "functions/sanitize.php" only scripts in the root 
directory can use it, otherwise a fatal error will be thrown
So you should include it along with autoload and init file in every page needs it

2 - Composer autoload file also follow the same rule you can't immport it here
*/