<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, UserRelation, Follow};
use view\post\Post as Post_View;
// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

$welcomeMessage = '';
if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
    $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
}

$current_user_id = $user->getPropertyValue("id");

$logo_path = $root . "assets/images/logos/large.png";
$index_page_path = $root . "index.php";
$left_panel_path = $root . "settings/components/left-panel.php";

$profile_selected = 'selected-button';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>V01D47</title>
<link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
<link rel="stylesheet" href="styles/global.css">
<link rel="stylesheet" href="styles/settings.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="javascript/config.js" defer></script>
<script src="javascript/settings.js" defer></script>
</head>
<body>
<main>
    <?php require_once "settings/components/left-panel.php" ?>
    <div id="global-container">
        <div id="setting-master-container">
            <h1 class="no-margin">Edit profile</h1>
            <div class="setting-block-line-separator"></div>
            <div>
                <div class="flex-column">
                    <label for="fullname" class="setting-label1">Display name</label>
                    <input type="text" class="setting-input-text-style" name="full-name" id="fullname">
                </div>
                <div class="flex-column">
                    <label for="fullname" class="setting-label1">Bio</label>
                    <textarea name="bio" id="bio" class="setting-input-text-style textarea-style"></textarea>
                </div>
                <div id="profile-asset-wrapper" class="flex">
                    <div id="assets-wrapper">
                        <div id="setting-cover-container">
                            <img src="assets/images/programming.png" class="setting-cover" alt="">
                        </div>
                        <div id="setting-container-image-and-names" class="flex padding16">
                            <div id="setting-picture-container" style="margin-right: 10px">
                                <img src="assets/images/read.png" alt="" id="setting-picture">
                            </div>
                            <div>
                                <h3 class="no-margin">MOUAD NASSRI</h3>
                                <p class="no-margin">@grotto</p>
                            </div>
                        </div>
                    </div>
                    <div id="setting-file-inputs-container">
                        <div>
                            <label for="cover-input" class="setting-label" style="font-size: 16px; margin-bottom: 5px">Cover</label>
                            <input type="file" name="cover" class="block" id="cover-input">
                            <p class="no-margin input-hint">PNG, GIF or JPG. At most 2 MB. Will be downscaled to 1500x500px</p>
                        </div>
                        <div style="margin-top: 10px">
                            <label for="avatar-input" class="setting-label" style="font-size: 16px; margin-bottom: 5px">Avatar</label>
                            <input type="file" name="avatar" class="block" id="avatar-input">
                            <p class="no-margin input-hint">PNG, GIF or JPG. At most 5 MB. Will be downscaled to 400x400px</p>
                        </div>
                        <div style="margin-top: 10px">
                            <label for="avatar-input" class="setting-label" style="font-size: 16px; margin-bottom: 5px">Private account</label>
                            <div class="flex">
                                <div class="toggle-button-style-2" id="private-account-button"></div>
                                <div id="private-account-status">(OFF)</div>
                            </div>
                            <input name="private" value="-1" id="private-account-state" type="hidden">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 26px">
                    <label for="fullname" class="setting-label1">Profile metadata</label>
                    <p class="input-hint">You can have up to 6 items displayed as a table on your profile</p>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label1" id="label1">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="full-name" id="label1">
                    </div>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label2" id="label2">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="label2" id="label2">
                    </div>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label3" id="label3">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="label3" id="label3">
                    </div>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label4" id="label4">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="label4" id="label4">
                    </div>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label5" id="label5">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="label5" id="label5">
                    </div>
                    <div class="flex">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Label" name="label6" id="label6">
                        <input type="text" class="setting-input-text-style meta-data-input" placeholder="Content" name="label6" id="label6">
                    </div>
                </div>

                <input type="submit" value="SAVE CHANGES" name="save-changes" id="save-button">
            </div>
        </div>
    </div>
</main>
</body>
</html>