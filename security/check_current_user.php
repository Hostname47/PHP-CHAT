<?php

/*
    IMPORTANT:
    Because this check is based on a stored resource in the client(hash for remember me feature) and current user(stored in the server)
    we can't put this script inside the API because the api is restful.
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../vendor/autoload.php";
require_once "../core/init.php";

use models\User;

require_once "../functions/sanitize_id.php";

$id = isset($_POST["current_user_id"]) ? $_POST["current_user_id"] : false;

if($id = sanitize_id($id)) {
    if(User::user_exists("id", $id) && $user->getPropertyValue("id") == $id) {
        echo json_encode(1);
    } else {
        echo json_encode(0);
    }
} else {
    echo json_encode(0);
}
