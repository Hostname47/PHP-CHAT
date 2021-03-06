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

while(true) {
    $channel_buffer = DB::getInstance()->query("SELECT * FROM `channel` WHERE sender = ? AND receiver = ?", array($receiver, $current_user_id))->results();
    $isEmpty = empty($channel_buffer);

    /* 
        If there's a message sent by $sender to the receiver (we check this in channel table by checking message array 
        returned from results) and the receiver is the same user who is loging in then we have a a message comming
        Actually checking the current user who is get the message is done in the query above when we put current_user_id
        to receiver query parameter :)
    */
    if(!$isEmpty) {
        $content = '';
        $chat_component = new ChatComponent();

        /*
            Why we only append one message per iteration :
            That's because the user could send more than one message, let's say 4 messages to his friend, well these 4
            messages are stored in the channel and wait for his friend to fetch them.
            Now the important part is that we fetch these messages one by one because we want to assign events belong to them
            one by one using javascript and delete the message immediately from channel because it's consumed by the receiver.
            if we append the 4 messages in one variable and add it to the chat-section it
            will not work

            NOTE: This code may be improved in the future
            HINT: T think some of what I wrote above is wrong, let's see
        */
        foreach($channel_buffer as $message) {
            $sender_user = new User();
            $sender_user->fetchUser("id", $message->sender);

            $msg = new Message();
            $msg->get_message("id", $message->message_id);
            $msg_obj = Message::get_message_obj("id", $message->message_id);

            $is_reply = $msg->get_property("reply_to");

            if($is_reply) {
                $original_message_id = $msg->get_property("reply_to");
                $reply_message_id = $msg->get_property("id");
                $reply_creator = $msg->get_property("message_sender");

                $original_message = new Message();
                $msg->get_message("id", $original_message_id);
                $original_creator = $original_message->get_property("message_sender");
                
                $content .= $chat_component->generate_received_reply_message($original_message_id, $reply_message_id, $original_creator, $reply_creator);
            } else {
                $content .= $chat_component->generate_friend_message($sender_user, $msg_obj, $msg->get_property("message_date"));
            }
            //$content .= $chat_component->generate_friend_message($sender_user, $msg_obj, $msg->get_property("message_date"));
        }

        echo json_encode($content);
        Message::dump_channel($receiver, $current_user_id);
        /*
            The following break get me stuck for more than 3 hours a row :-(
            when I was reviewing the code, I thought that this break is uneccessary but later, I realize that this script
            need to be stopped in some place, and the case is when he found some new data, that script echo out the content
            and exit and then when the code in javascript get that data it send the request again ! and so this break is
            the foundation of this script
        */
        break;
    }

    // wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
    usleep(10000);
}