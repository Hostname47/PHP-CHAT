<?php

namespace layouts\search;

use classes\Config;
use models\{Follow};

class Search {
    public function generateSearchPerson($current_user_id, $user) {
        // Notice we fetch data of users like they are objects; That's because search function get users as objects
        $picture = (!empty($user->picture)) ? Config::get("root/path") . $user->picture : Config::get("root/path") . "public/assets/images/logos/logo512.png";
        $fullname = $user->firstname . " " . $user->lastname;
        $username = $user->username;
        $id = $user->id;

        $follower_id = $current_user_id;
        $followed_id = $user->id;

        $follow = new Follow();
        $follow->set_data(array(
            "follower"=>$follower_id,
            "followed"=>$followed_id
        ));

        if($follow->fetch_follow()) {
            $follow_btn = <<<F_BTN
                <input type="submit" class="button-style-9 follow-button followed-user" value="Followed" style="margin-left: 4px; font-weight: 400">
            F_BTN;
        } else {
            $follow_btn = <<<F_BTN
                <input type="submit" class="button-style-9 follow-button follow-user" value="Follow" style="margin-left: 4px; font-weight: 400">
            F_BTN;
        }

        return <<<QQ
        <div class="search-result-item flex-space search-result-person">
            <div class="flex">
                <div class="search-result-item-picture-container">
                    <img src="{$picture}" class="search-result-item-picture" alt="">
                </div>
                <div style="margin-left: 8px">
                    <h1 class="title-style-6">{$fullname}</h1>
                    <p class="label-style-2">@{$username}</p>
                </div>
            </div>
            <form action="" method="GET" class="flex follow-form">
                <input type="hidden" name="current_user_id" value="$follower_id">
                <input type="hidden" name="current_profile_id" value="$followed_id">
                $follow_btn
            </form>
            <input type="hidden" value="{$id}" class="uid">
        </div>
QQ;
    }

    public function generateSearchGroup() {
        return <<<QQ
        <div class="search-result-item">
            <div class="row-v-flex">
                <img src="public/assets/images/read.png" class="image-style-6" alt="">
                <div style="margin-left: 8px">
                    <h1 class="title-style-6">Loupgarou</h1>
                    <p class="label-style-2">3.5K members</p>
                </div>
            </div>
        </div>
QQ;
    }
}