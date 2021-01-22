<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\User;

$user = new User();

// Id should be set and numeric and should be there in the database, otherwise error message will be shown
if(isset($_GET["username"])) {
    $username = trim(htmlspecialchars($_GET["username"]));

    if($user->fetchUser("username", $username)) {
        echo json_encode(array(
            "user"=>$user,
            "success"=>true
        ));
    } else {
        echo json_encode(array(
            "success"=>false
        ));
    }
}
