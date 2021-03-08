<?php

namespace layouts\master_right;
use classes\Config;

    class Right {
        public static function generateFriendContact($current_user_id, $user) {
            $user_id = $user->getPropertyValue("id");
            $user_name = $user->getPropertyValue("username");
            $user_picture = Config::get("root/path") . (empty($user->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));
            if(strlen($user_name) > 15) {
                $user_name = substr($user_name, 0, 15) . " ..";
            }

            $now = strtotime(date("Y/m/d h:i:s"));
            $last_active_date = strtotime($user->getPropertyValue("last_active_update"));
            $interval  = abs($last_active_date - $now);
            $minutes   = round($interval / 60);

            $online_status = ($minutes < 5) ? "online.png" : "offline.png";

            // Here we need to implement some code to see if the yuser is online or not

            echo <<<EOS
            <div class="contact-user">
                <div class="contact-user-picture-container">
                    <img src="$user_picture" class="contact-user-picture" alt="">
                </div>
                <p class="contact-user-name">$user_name</p>
                <div class="row-v-flex right-pos-margin">
                    <div class="contact-item-buttons-container">
                        <a href="" class="contact-go-to-chat contact-item-button"></a>
                        <a href="" class="contact-go-to-profile contact-item-button"></a>
                        <input type="hidden" class="uid" value="$user_id">
                        <input type="hidden" class="current" value="$current_user_id">
                    </div>
                    <img src="public/assets/images/icons/$online_status" class="image-style-4 contact-user-connection-icon" alt="">
                </div>
            </div>
EOS;
        }
    }
?>