<?php

/*
    IMPORTANT:
    Because this check is based on a stored resource in the client(hash for remember me feature) and current user(stored in the server)
    we can't put this script inside the API because the api is restful.
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../vendor/autoload.php";
require_once "../core/init.php";

echo json_encode($user);