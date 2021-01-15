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
        $followers = Follow::get_user_followers($user_id);
        if(count($followers) > 0) {
            $followers = json_encode($followers);
            echo json_encode(
                array(
                    "followers"=>$followers,
                    "message"=>"followers return successfully !",
                    "success"=>true
                )
            );
        } else {
            echo json_encode(
                array(
                    "followers"=>null,
                    "message"=>"This user has no followers.",
                    "success"=>true
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"user id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}