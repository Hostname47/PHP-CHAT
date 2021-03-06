<?php

require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use models\{User, UserRelation};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_text.php";

// Get the username
$current_user_id = $user->getPropertyValue("id");
$username = isset($_POST["username"]) ? sanitize_text($_POST["username"]) : "";

if(!empty($username)) {
    $user_relation = new UserRelation();
    $friends = $user_relation->get_friends($current_user_id);
    
    $content = '';
    foreach($friends as $friend) {
        if(strpos($friend->getPropertyValue("username"), $username) !== false) {
            $content .= ChatComponent::generate_chat_page_friend_contact($current_user_id, $friend);
        }
    }
    echo $content;
} else {
    $user_relation = new UserRelation();
    $friends = $user_relation->get_friends($current_user_id);
    
    $content = '';
    foreach($friends as $friend) {
        $content .= ChatComponent::generate_chat_page_friend_contact($current_user_id, $friend);
    }
    echo $content;
}