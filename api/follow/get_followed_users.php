<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, Follow};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

if(!isset($_GET["user_id"])) {
    echo json_encode(
        array(
            "message"=>"You should provide user_id as query string parameter along with path",
            "success"=>false
        )
    );

    exit();
}

$user_id = $_GET["user_id"];

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($user_id = sanitize_id($_GET["user_id"])) && 
    User::user_exists("id", $user_id)) {
        // If the user has followers(at least one we get them as array and encode them and return the json array)
        $followed_users = Follow::get_followed_users($user_id);
        if(count($followed_users) > 0) {
            $followed_users = json_encode($followed_users);
            echo json_encode(
                array(
                    "followers"=>$followed_users,
                    "message"=>"followed users return successfully !",
                    "success"=>true
                )
            );
        } else {
            echo json_encode(
                array(
                    "followers"=>null,
                    "message"=>"This user follows no one.",
                    "success"=>true
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"user with id: $user_id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}