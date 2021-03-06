<?php
require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\DB;
use models\{User, Message};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_write_close();
ignore_user_abort(true);
set_time_limit(0);

$current_user_id = $user->getPropertyValue("id");
$receiver = isset($_POST["receiver"]) ? $_POST["receiver"] : null;

$channel_buffer = DB::getInstance()->query("SELECT * FROM `writing_message_notifier` WHERE message_writer = ? AND message_waiter = ?", array($receiver, $current_user_id))->results();
$isEmpty = empty($channel_buffer);

if(!$isEmpty) {
    echo json_encode(array(
        "finished"=>false
    ));
} else {
    echo json_encode(array(
        "finished"=>true
    ));
}