<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{Message};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";

$message_id = sanitize_id($_POST["message_id"]);
$is_received = sanitize_id($_POST["is_received"]);

if(Message::exists($message_id)) {
    /*
        Here notice that you have to check if the current user is the one who receive the message before delete the message
        to maintain the access to messages and the received users only could delete messages sent to them.
        Before calling this, call check current user to check the current user id, then delete the message
    */

    $message_manager = new Message();
    $message_manager->set_property("id", $message_id);

    if($is_received == 'yes') {
        $message_manager->delete_received_message();
    } else {
        $message_manager->delete_sended_message();
    }

    echo json_encode(array(
        "success"=>true,
        "message"=>'message deleted successfully !'
    ));
} else {
    echo json_encode(array(
        "success"=>false,
        "message"=>'message not exists'
    ));
}

