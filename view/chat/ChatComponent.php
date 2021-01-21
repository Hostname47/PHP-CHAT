<?php

namespace view\chat;
use classes\Config;

    class ChatComponent {
        public static function generate_chat_page_friend_contact($user) {
            $user_id = $user->getPropertyValue("id");
            $user_name = $user->getPropertyValue("username");
            $user_picture = Config::get("root/path") . (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));
            if(strlen($user_name) > 25) {
                $user_name = substr($user_name, 0, 25) . " ..";
            }

            // Here we need to implement some code to see if the yuser is online or not

            echo <<<EOS
            <div class="friends-chat-item">
                <img src="$user_picture" class="image-style-3 contact-user-picture" alt="">
                <p class="regular-text" style="margin-left: 8px">$user_name</p>
                <img src="assets/images/icons/online.png" class="image-style-4 right-pos-margin" alt="">
                <input type="hidden" value="$user_id">
            </div>
EOS;
        }
    }
?>