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
$message = sanitize_id($_POST["message"]);
$message_date = date("Y/m/d H:i:s");

if(($sender) && 
    User::user_exists("id", $sender)) {
        if($receiver && 
            User::user_exists("id", $receiver)) {
                $sender_user = new User();
                $sender_user->fetchUser("id", (int)$sender);
                
                $message_model = new Message();
                $message_model->set_data(array(
                    "sender"=>$sender,
                    "receiver"=>$receiver,
                    "message"=>$message,
                    "message_date"=>$message_date
                ));

                $res = $message_model->add();

                $message_obj = Message::get_message_obj("id", $res);

                $chat_wrapper = new ChatComponent();
                $chat_component = $chat_wrapper->generate_current_user_message($sender_user, $message_obj, $message_date);

                echo $chat_component;

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