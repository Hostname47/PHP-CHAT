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

$is_reply = false;
$sender = sanitize_id($_POST["sender"]);
$receiver = sanitize_id($_POST["receiver"]);
$message = sanitize_id($_POST["message"]);
$message_date = date("Y/m/d H:i:s");

if(isset($_POST["is_reply"])) {
    $is_reply = true;
    $replied_message_id = sanitize_id($_POST["replied_message_id"]);
}

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

                if($is_reply) {
                    $message_model->set_property("is_reply", 1);
                    //$res = $message_model->add();
                    /*
                        We pass the id of message inserted to add_reply in order for reply row to know which message this
                        reply is connected to !
                    */
                    //$message_model->add_reply($res);

                    // After that we fetch the message
                    //$message_obj = Message::get_message_obj("id", $res);
                    // This will get message with id 260 just for reply component build tests
                    $message_obj = Message::get_message_obj("id", 260);

                    // Then we generate a replied message components
                    $chat_wrapper = new ChatComponent();
                    $chat_component = $chat_wrapper->generate_reply_message($sender_user, $message_obj, $message_date);
                } else {
                    $res = $message_model->add();
    
                    $message_obj = Message::get_message_obj("id", $res);
    
                    $chat_wrapper = new ChatComponent();
                    // Here we need to pass the original message id, reply_message_id, original message creator and reply creator
                    //$chat_component = $chat_wrapper->generate_current_user_message();
                }

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