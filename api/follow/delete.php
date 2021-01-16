<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, Follow};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

if(!isset($_POST["current_user_id"])) {
    echo json_encode(
        array(
            "message"=>"You should provide current_user_id as post form input",
            "success"=>false
        )
    );

    exit();
}
if(!isset($_POST["current_profile_id"])) {
    echo json_encode(
        array(
            "message"=>"You should provide followed_id as post form input",
            "success"=>false
        )
    );

    exit();
}

$follower = $_POST["current_user_id"];
$followed = $_POST["current_profile_id"];

/*
    Here we can't allow user to follow himself because we create a UNIQUE constraint(follower_id, followed_id) in the database,
    If you want to allow user follow himself, remove the constraint and also remove the following if statement
*/
if($follower === $followed) {
    echo json_encode(
        array(
            "message"=>"You can't unfollow yourself",
            "success"=>false
        )
    );

    exit();
}

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($follower = sanitize_id($follower)) && 
    User::user_exists("id", $follower)) {
        // Same check here with the followed user
        if(($followed = sanitize_id($followed)) && 
            User::user_exists("id", $followed)) {
                if(Follow::follow_exists($follower, $followed)) {

                    $follow = new Follow();
                    
                    $follow->set_data(array(
                        "follower"=>$follower,
                        "followed"=>$followed
                    ));

                    $follow->fetch_follow();

                    $follow->delete();

                    echo json_encode(
                        array(
                            "message"=>"The follower with id: $follower unfollows the user with id: $followed successully !",
                            "success"=>true
                        )
                    );
                } else {
                    echo json_encode(
                        array(
                            "message"=>"The user with id: $follower cannot unfollow the user with id: $followed because he is not followed him !",
                            "success"=>false
                        )
                    );
                }
        } else {
            echo json_encode(
                array(
                    "message"=>"followed id is either not valid or not exists in our db",
                    "success"=>false
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"follower id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}