
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
        <section id="firt-section">
            <div class="relative flex-column">
                <img src="" id="cover-photo" alt="">
                <img src="assets/images/logos/logo512.png" id="profile-picture" alt="">
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
                    <a href="" class="button-style-2">Edit profile</a>
                </div>
            </div>
        </section>
        <section id="second-section">
            <div>
                <div class="user-info-section">
                    
                </div>
                <div class="user-info-section">
                    
                </div>
                <div class="user-info-section">
                    
                </div>
            </div>
            <div id="posts-container">
                <div class="post-item">

                </div>
            </div>
        </section>
    </main>
</body>
</html>