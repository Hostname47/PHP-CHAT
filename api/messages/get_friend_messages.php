<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, Message};
use view\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";

$sender = sanitize_id($_POST["sender"]);
$receiver = sanitize_id($_POST["receiver"]);

// Check if the follower id is set, and if it is numeric by calling sanitize_id, and exists in the database using user_exists
if(($sender) && 
    User::user_exists("id", $sender)) {
        // Same check here with the followed user
        if($receiver && 
            User::user_exists("id", $receiver)) {
                
                $sender_user = new User();
                $sender_user->fetchUser("id", $sender);
                $receiver_user = new User();
                $receiver_user->fetchUser("id", $receiver);

                $chat_component = new ChatComponent();

                $sender_to_receiver = Message::getMessages($sender, $receiver);
                $receiver_to_sender = Message::getMessages($receiver, $sender);
                $messages = array_merge($sender_to_receiver, $receiver_to_sender);

                function sortFunction($a, $b) {
                    return strtotime($a->create_date) - strtotime($b->create_date);
                }
                
                usort($messages, "sortFunction");
                $content = '';

                foreach($messages as $message) {
                    if($message->message_creator == $sender) {
                        $content .= $chat_component->generate_current_user_message($sender_user, $message->message, $message->create_date);
                    } else {
                        $content .= $chat_component->generate_friend_message($receiver_user, $message->message, $message->create_date);
                    }
                }
                Message::dump_channel($receiver, $sender);
                echo $content;

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