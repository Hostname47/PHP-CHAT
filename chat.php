
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>V01D47</title>
<link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
<link rel="stylesheet" href="styles/global.css">
<link rel="stylesheet" href="styles/header.css">
<link rel="stylesheet" href="styles/chat.css">
<link rel="stylesheet" href="styles/master-left-panel.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="javascript/config.js" defer></script>
<script src="javascript/header.js" defer></script>
<script src="javascript/global.js" defer></script>
<script src="javascript/chat.js" defer></script>
</head>
<body>
<?php include_once "components/basic/header.php"; ?>
<main>
    <div id="global-container">
        <?php include_once "components/basic/master-left.php"; ?>
        <div id="master-middle">
            <div class="green-message">
                <p class="green-message-text"><?php echo $welcomeMessage; ?></p>
                <script type="text/javascript" defer>
                    if($(".green-message-text").text() !== "") {
                        $(".green-message").css("display", "block");
                    }
                </script>
            </div>
            <div id="chat-global-container">
                <div id="first-chat-part">
                    <div class="friends-chat-search-container">
                        <input type="text" class="chat-input-style" placeholder="Search for conversations">
                    </div>
                    <div id="friend-chat-discussions-container">
                        <div class="friend-chat-discussion-item-wraper relative">
                            <div class="chat-disc-user-image">
                                <img src="assets/images/read.png" class="image-style-7" alt="">
                            </div>
                            <div>
                                <div class="chat-disc-name-and-username">
                                    <p class="bold-text-style-1">Mouad Nassri</p><span class="chat-disc-item-username"> @grotto</span>
                                </div>
                                <p class="regular-text">Last message</p>
                            </div>
                            <div class="right-pos-margin">
                                <p class="regular-text-style-2">34min</p>
                            </div>
                            <div class="selected-chat-discussion">

                            </div>
                            <input type="hidden" class="uid">
                        </div>
                        <div class="friend-chat-discussion-item-wraper relative">
                            <div class="chat-disc-user-image">
                                <img src="assets/images/read.png" class="image-style-7" alt="">
                            </div>
                            <div>
                                <div class="chat-disc-name-and-username">
                                    <p class="bold-text-style-1">Mouad Nassri</p><span class="chat-disc-item-username"> @grotto</span>
                                </div>
                                <p class="regular-text">Last message</p>
                            </div>
                            <div class="right-pos-margin">
                                <p class="regular-text-style-2">34min</p>
                            </div>
                            <input type="hidden" class="uid">
                            <div class="selected-chat-discussion" style="display: block">
                                
                            </div>
                        </div>
                        <div class="friend-chat-discussion-item-wraper relative">
                            <div class="chat-disc-user-image">
                                <img src="assets/images/read.png" class="image-style-7" alt="">
                            </div>
                            <div>
                                <div class="chat-disc-name-and-username">
                                    <p class="bold-text-style-1">Mouad Nassri</p><span class="chat-disc-item-username"> @grotto</span>
                                </div>
                                <p class="regular-text">Last message</p>
                            </div>
                            <div class="right-pos-margin">
                                <p class="regular-text-style-2">Jan 5</p>
                            </div>
                            <input type="hidden" class="uid">
                            <div class="selected-chat-discussion">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div id="second-chat-part" class="relative">
                    <div id="chat-header">
                        <div class="chat-disc-user-image">
                            <img src="assets/images/read.png" class="image-style-7" alt="">
                        </div>
                        <a href="" class="no-underline">
                            <div class="chat-disc-name-and-username">
                                <p class="bold-text-style-1">Mouad Nassri</p>
                            </div>
                            <span> @grotto</span>
                        </a>

                        <div class="right-pos-margin">
                            <a href="" class="chat-header-more-button dotted-more-back"></a>
                        </div>
                    </div>
                    <div id="chat-container" class="relative">
                        <div class="chat-date">
                            <p class="regular-text" style="text-align: center; margin: 14px 0 20px 0">Jan 19, 2021 9:50 PM</p>
                        </div>
                        <div class="message-global-container">
                            <div class="current-user-message-container">
                                <div class="relative">
                                    <div class="chat-message-more-button-container">
                                        <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                                    </div>
                                    <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                        <div class="options-container-style-1 black">
                                            <div class="sub-option-style-2">
                                                <a href="" class="black-link">Delete message (under construction)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute current-user-message-date message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
                                    </div>
                                </div>
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                            </div>
                        </div>
                        
                        <div class="message-global-container">
                            <div class="friend-message-container">
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute message-date friend-message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
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
                        <div class="message-global-container">
                            <div class="current-user-message-container">
                                <div class="relative">
                                    <div class="chat-message-more-button-container">
                                        <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                                    </div>
                                    <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                        <div class="options-container-style-1 black">
                                            <div class="sub-option-style-2">
                                                <a href="" class="black-link">Delete message (under construction)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute current-user-message-date message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
                                    </div>
                                </div>
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                            </div>
                        </div>
                        
                        <div class="message-global-container">
                            <div class="friend-message-container">
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute message-date friend-message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
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
                        <div class="message-global-container">
                            <div class="current-user-message-container">
                                <div class="relative">
                                    <div class="chat-message-more-button-container">
                                        <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                                    </div>
                                    <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                        <div class="options-container-style-1 black">
                                            <div class="sub-option-style-2">
                                                <a href="" class="black-link">Delete message (under construction)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute current-user-message-date message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
                                    </div>
                                </div>
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                            </div>
                        </div>
                        
                        <div class="message-global-container">
                            <div class="friend-message-container">
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute message-date friend-message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
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
                        <div class="message-global-container">
                            <div class="current-user-message-container">
                                <div class="relative">
                                    <div class="chat-message-more-button-container">
                                        <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                                    </div>
                                    <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                        <div class="options-container-style-1 black">
                                            <div class="sub-option-style-2">
                                                <a href="" class="black-link">Delete message (under construction)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute current-user-message-date message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
                                    </div>
                                </div>
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                            </div>
                        </div>
                        
                        <div class="message-global-container">
                            <div class="friend-message-container">
                                <a href=""><img src="assets/images/read.png" class="image-style-10" alt=""></a>
                                <div class="message-wrapper relative">
                                    <p class="regular-text message-text">This is current user message</p>
                                    <div class="absolute message-date friend-message-date">
                                        <p class="regular-text-style-2">Jan 19, 2021 9:50 PM</p>
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
                    </div>
                    <div class="message-input-box">
                        <form action="" id="send-message-form">
                        </form>
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
                        <input type="text" form="send-message-form" placeholder="Type a new message" class="chat-input-style">
                        <input type="submit" value="send" form="send-message-form" id="send-message-button">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>