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
        "token_name" => "token",
        "tokens"=>array(
            "register"=>"register",
            "login"=>"login",
            "reset-pasword"=>"reset-pasword",
            "share-post"=>"share-post",
            "saveEdits"=>"saveEdits",
            "logout"=>"logout"
        )
    ),
    "root"=> array(
        'path'=>'http://127.0.0.1/CHAT/'
    )
);