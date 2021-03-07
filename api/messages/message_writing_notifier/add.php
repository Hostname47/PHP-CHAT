<?php


require_once "../../../vendor/autoload.php";
require_once "../../../core/rest_init.php";

use models\{User, Message};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../../functions/sanitize_id.php";

$sender = sanitize_id($_POST["sender"]);
$receiver = sanitize_id($_POST["receiver"]);

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($sender) && 
    User::user_exists("id", $sender)) {
        // Same check here with the followed user
        if($receiver && 
            User::user_exists("id", $receiver)) {
                
                $message_model = new Message();

                $message_model->set_property("message_sender", $sender);
                $message_model->set_property("message_receiver", $receiver);

                $message_model->add_writing_message_notifier();
        } else {
            echo json_encode(
                array(
                    "message"=>"receiver's id is either not valid or not exists in our db",
                    "success"=>false
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"sender's id is either not valid or not exists in our db",
            "success"=>false
        )
    );
}