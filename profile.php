
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

if(isset($_POST["logout"])) {
    if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
        $user->logout();
        Redirect::to("login/login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - Profile</title>
    <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/profile.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/profile.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <section id="first-section">
            <div class="relative flex-column">
                <div>
                    <div id="cover-container">
                        <img src="assets/images/programming.png" class="cover-photo" alt="">
                    </div>
                    <div class="viewer">
                        <div class="relative">
                            <a href="" class="close-viewer-style close-viewer"></a>
                        </div>
                        <img src="" class="profile-cover-picture-preview" alt="User's name cover picture">
                    </div>
                </div>
                <div id="profile-picture-container">
                    <div class="relative" style="border-radius: 50%">
                        <img src="assets/images/read.png" class="profile-picture" alt="">
                        <img src="" class="profile-picture shadow-profile-picture absolute" alt="">
                    </div>
                    <div class="viewer">
                        <div class="relative">
                            <a href="" class="close-viewer-style close-viewer"></a>
                        </div>
                        <img src="" class="profile-picture-preview" alt="User's name Profile picture">
                    </div>
                </div>
            </div>
            <div id="name-and-username-container">
                <h1 class="title-style-3"><?php echo $user->getPropertyValue("firstname") . " " . $user->getPropertyValue("lastname"); ?></h1>
                <p class="regular-text-style-1">@<?php echo $user->getPropertyValue("username"); ?></p>
            </div>
            <div class="flex-space" id="owner-profile-menu-and-profile-edit">
                <div id="profile-menu">
                    <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
                    <a href="" class="profile-menu-item">Photos</a>
                    <a href="" class="profile-menu-item">Videos</a>
                    <a href="" class="profile-menu-item">Likes</a>
                    <a href="" class="profile-menu-item">Comments</a>
                </div>
                <div>
                    <a href="" class="button-style-2" id="edit-profile-button">Edit profile</a>
                    <div class="viewer" style="display: block">
                        <div id="edit-profile-container">
                            <div class="flex-space" id="edit-profile-header">
                                <a href="" class="close-style-1 close-viewer"></a>
                                <h2 class="title-style-5 black">Edit profile</h2>
                                <a href="" class="button-style-3">Save</a>
                            </div>
                            <div id="picture-and-cover-container">
                                <a href="" id="change-cover-button">
                                    <div id="cover-changer-container" class="relative">
                                        <img src="assets/images/icons/change-image.png" class="absolute image-size-1 change-image-icon" alt="">
                                        <img src="" id="cover-changer-shadow" style="z-index: 0" class="absolute" alt="">
                                        <img src="assets/images/programming.png" id="cover-changer-dim" alt="">
                                    </div>
                                </a>
                                <div class="relative flex-justify">
                                    <a href="" id="change-picture-button" class="absolute">
                                        <div id="picture-changer-container" class="relative">
                                            <img src="assets/images/read.png" class="former-picture-dim" alt="">
                                            <img src="assets/images/icons/change-image.png" class="absolute image-size-1 change-image-icon" alt="">
                                            <img src="" class="former-picture-dim former-picture-shadow absolute" style="z-index: 0" alt="">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div id="textual-data-edit">
                                <div class="field-style-1">
                                    <label for="display-name" class="label-style-1">Display name</label>
                                    <input type="text" class="input-style-1" name="display-name">
                                </div>
                                <div class="field-style-1">
                                    <label for="bio" class="label-style-1">Bio</label>
                                    <textarea type="text" class="textarea-style-1" placeholder="Add your bio.." name="bio"></textarea>
                                </div>
                                <div class="field-style-1">
                                    <label for="private" class="label-style-1">Private account</label>
                                    <input type="text" class="input-style-1" name="display-name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="second-section">
            <div>
                <div class="user-info-section row-v-flex">
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">2.9K</h2>
                            <p class="regular-text-style-2">Posts</p>
                        </div>
                    </a>
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">10</h2>
                            <p class="regular-text-style-2">Followers</p>
                        </div>
                    </a>
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">156</h2>
                            <p class="regular-text-style-2">Following</p>
                        </div>
                    </a>
                </div>
                <div class="user-info-section">
                    <h2 class="title-style-4">About</h2>
                    <p class="calendar-icon regular-text-style-2">Member since <!-- Join date should be fetched from db -->March 2020</p>
                </div>
                <div class="user-info-section">
                    <div class="flex-space">
                        <h2 class="title-style-4">Media</h2>
                        <a href="" class="link-style-1">Show all</a>
                    </div>
                    <div>
                        <div class="user-info-square-shapes-container">
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                        </div>
                        <div class="user-info-square-shapes-container">
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="posts-container">
                <div class="post-item1">

                </div>    
                <div class="post-item">

                </div>
                <div class="post-item">

                </div>
            </div>
        </section>
    </main>
</body>
</html>