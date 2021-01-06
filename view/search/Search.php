<?php

namespace view\search;

use classes\Config;

class Search {
    public function generateSearchPerson($user) {
        // Notice we fetch data of users like they are objects; That's because search function get users as objects
        $picture = (!empty($user->picture)) ? $user->picture : Config::get("root/path") . "assets/images/icons/user.png";
        $fullname = $user->firstname . " " . $user->lastname;
        $username = $user->username;
        
        return <<<QQ
        <div class="search-result-item flex-space">
            <div class="flex">
                <img src="{$picture}" class="image-style-1" alt="">
                <div style="margin-left: 8px">
                    <h1 class="title-style-6">{$fullname}</h1>
                    <p class="label-style-2">@{$username}</p>
                </div>
            </div>
            <a href="" class="button-style-4 follow-user">Follow</a>
        </div>
QQ;
    }

    public function generateSearchGroup() {
        return <<<QQ
        <div class="search-result-item">
            <div class="row-v-flex">
                <img src="assets/images/read.png" class="image-style-6" alt="">
                <div style="margin-left: 8px">
                    <h1 class="title-style-6">Loupgarou</h1>
                    <p class="label-style-2">3.5K members</p>
                </div>
            </div>
        </div>
QQ;
    }
}