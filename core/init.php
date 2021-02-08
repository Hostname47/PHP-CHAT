<?php

use classes\{Cookie, DB, Config, Session};
use models\User;

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
            "saveEdits"=>"saveEdits",
            "share-post"=>"share-post",
            "logout"=>"logout"
        )
    ),
    "root"=> array(
        'path'=>'http://127.0.0.1/CHAT/',
        'project_name'=>"CHAT"
    )
);

/*

Here we create a user object with no data associated to it, and in the user constructor, we check if there's already a session
if so we get the data from session which is the user id, and we fetch data from database of that id and we see if that id really
exists in database, if it is, WE ASSIGN TRUE TO isLoggedIn 

Then we check if there's a cookie set in user machine and there's no session (This case is like we switch user's computer and later tries to logged in)
in this case we fetch the hash of user's machine and see if this hash exists in users_session table in database, if hash matches we fetch user_id associated with it 
and use it to fetch user with that id. if the count of fetching is 1 then we give username, password and true($remember=true) to login function

go to login function's comment

*/

/*
    Notice that getting the root path is a common case for almost every page, so it's a good idea to put it in $root
    variable and only use root variable to reference it because init file also included in every page deal with config
*/
$root = Config::get("root/path");
$proj_name = Config::get("root/project_name");

$user = new User();

if(Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))) {
    $hash = Cookie::get(Config::get("remember/cookie_name"));
    $res = DB::getInstance()->query("SELECT * FROM users_session WHERE hash = ?", array($hash));

    if($res->count()) {
        $user->fetchUser("id", $res->results()[0]->user_id);
        $user->login($user->getPropertyValue("username"),$user->getPropertyValue("password"),true);
    }
}

if($user->getPropertyValue("isLoggedIn")) {
    $user->update_active();
}

/* 
IMPORTANT : 
1 - sanitize function file could not be included here because the path will be relative to the caller script
so if for example include it like following: include_once "functions/sanitize.php" only scripts in the root 
directory can use it, otherwise a fatal error will be thrown
So you should include it along with autoload and init file in every page needs it

2 - Composer autoload file also follow the same rule you can't import it here
*/