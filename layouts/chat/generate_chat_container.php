<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

if(!isset($_POST["sender"])) {
    echo json_encode(
        array(
            "message"=>"You should provide current_user_id as post form input",
            "success"=>false
        )
    );

    exit();
}
if(!isset($_POST["receiver"])) {
    echo json_encode(
        array(
            "message"=>"You should provide followed_id as post form input",
            "success"=>false
        )
    );

    exit();
}

$sender = sanitize_id($_POST["sender"]);
$receiver = sanitize_id($_POST["receiver"]);

if(($sender) && 
    User::user_exists("id", $sender)) {
        if(sanitize_id($receiver) && 
            User::user_exists("id", $receiver)) {
                $chat_container = ChatComponent::generate_chat_section($sender, $receiver);
                return array(
                    $chat_container,
                    "success"=>true
                );
        } else {
            echo json_encode(
                array(
                    "message"=>"sender id is either not valid or not exists in our db",
                    "success"=>false
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"sender id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}