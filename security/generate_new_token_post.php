<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../vendor/autoload.php";
require_once "../core/init.php";

use models\User;
use classes\{Session, Token};

require_once "../functions/sanitize_id.php";

$token_name = isset($_POST["token_name"]) ? $_POST["token_name"] : '';

if(Session::exists('share-post'))
    echo Session::get('share-post');
else {
    echo Token::generate('share-post');
}