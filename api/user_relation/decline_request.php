<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, UserRelation};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

$current_user = sanitize_id($_POST["current_user_id"]);
$friend = sanitize_id($_POST["current_profile_id"]);

/*
    Here we can't allow user to follow himself because we create a UNIQUE constraint(follower_id, followed_id) in the database,
    If you want to allow user follow himself, remove the constraint and also remove the following if statement
*/
if($current_user === $friend) {
    echo json_encode(
        array(
            "message"=>"You can't add yourself or cancel request",
            "success"=>false
        )
    );

    exit();
}

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($current_user) && 
    User::user_exists("id", $current_user)) {
        // Same check here with the followed user
        if($friend && 
            User::user_exists("id", $friend)) {
            
            $user_relation = new UserRelation();

            $user_relation->set_property("from", $friend);
            $user_relation->set_property("to", $current_user);

            if($user_relation->delete_relation()) {
                echo json_encode(
                    array(
                        "message"=>"user with id: $current_user decline a request that was sent to user with id: $friend successfully !",
                        "success"=>true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "message"=>"user with id: $friend did not send a friend request to user with id: $current_user",
                        "success"=>false
                    )
                );
            }

        } else {
            echo json_encode(
                array(
                    "message"=>"friend's id is either not valid or not exists in our db",
                    "success"=>false
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"your id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}