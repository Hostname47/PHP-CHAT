<?php

namespace view\chat;

use classes\Config;
use models\User;

    class ChatComponent {
        public static function generate_chat_page_friend_contact($current_user_id, $user) {
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
                <input type="hidden" class="sender" value="$current_user_id">
                <input type="hidden" class="receiver" value="$user_id">
            </div>
EOS;
        }

        public static function generate_chat_section($sender, $receiver) {
            $snd = new User();
            $snd->fetchUser("id", $sender);
            $rcv = new User();
            $rcv->fetchUser("id", $receiver);

            $friend_fullname = $rcv->getPropertyValue("firstname") . " " . $rcv->getPropertyValue("lastname");
            $friend_username = $rcv->getPropertyValue("username");
            $friend_picture = empty($rcv->getPropertyValue("picture")) ? "assets/images/logos/logo512.png" : $rcv->getPropertyValue("picture");

            echo <<<CHAT_SECTION
                <div id="second-chat-part" class="relative">
                    <input type="hidden" class="chat-sender" value="$sender">
                    <input type="hidden" class="chat-receiver" value="$receiver">
                    <div id="chat-header">
                        <div class="chat-disc-user-image">
                            <img src="$friend_picture" class="image-style-7" alt="">
                        </div>
                        <a href="" class="no-underline">
                            <div class="chat-disc-name-and-username">
                                <p class="bold-text-style-1">$friend_fullname</p>
                            </div>
                            <span>@$friend_username</span>
                        </a>

                        <div class="right-pos-margin">
                            <a href="" class="chat-header-more-button dotted-more-back"></a>
                        </div>
                    </div>
                    <div id="chat-container" class="relative">
                        <div class="chat-date">
                            <p class="regular-text" style="text-align: center; margin: 14px 0 20px 0">Jan 19, 2021 9:50 PM</p>
                        </div>
                    </div>
                    <div class="message-input-box">
                        <div class="relative">
                            <a href="" class="chat-message-settings-button white-dotted-more-back"></a>

                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" form="send-message-form" placeholder="Type a new message" id="chat-text-input" class="chat-input-style send-button">
                        <input type="submit" value="send" form="send-message-form" id="send-message-button">
                    </div>        
                <div>
CHAT_SECTION;
        }

        public static function generate_current_user_message($user, $message, $message_date) {
            $user_profile = Config::get("root/path") .  (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.PNG" : $user->getPropertyValue("picture"));
            $user_profile_link = Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");
            $message_date = date("F d \a\\t Y h:i A",strtotime($message_date));
            return <<<CUM
                <div class="message-global-container">
                    <div class="current-user-message-container">
                        <div class="relative">
                            <div class="chat-message-more-button-container">
                                <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                            </div>
                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1;left: -150px">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="message-wrapper relative">
                            <p class="regular-text message-text">$message</p>
                            <div class="absolute current-user-message-date message-date">
                                <p class="regular-text-style-2">$message_date</p>
                            </div>
                        </div>
                        <a href="$user_profile_link"><img src="$user_profile" class="image-style-10" alt=""></a>
                    </div>
                </div>
CUM;
        }

        public static function generate_friend_message($user, $message, $message_date) {
            $user_profile = Config::get("root/path") .  (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.PNG" : $user->getPropertyValue("picture"));
            $user_profile_link = Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");
            $message_date = date("F d \a\\t Y h:i A",strtotime($message_date));
            return <<<FM
                <div class="message-global-container">
                    <div class="friend-message-container">
                        <a href="$user_profile_link"><img src="$user_profile" class="image-style-10" alt=""></a>
                        <div class="message-wrapper relative">
                            <p class="regular-text message-text">$message</p>
                            <div class="absolute message-date friend-message-date">
                                <p class="regular-text-style-2">$message_date</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="chat-message-more-button-container">
                                <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                            </div>
                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Reply (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
FM;
        }
    }
?>