
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, UserRelation, Follow};
use view\post\Post as Post_View;
use view\chat\ChatComponent;
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
                <div id="first-chat-part" class="relative">
                    <div id="styled-border" class="absolute">
                    
                    </div>
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
                            <div class="selected-chat-discussion">
                                
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
                    <div class="friends-chat-search-container" style="border-right: none;">
                        <input type="text" class="chat-input-style friend-search-input" placeholder="Search for a friend to chat with">
                    </div>
                    <div id="friends-chat-container" class="relative">
                        <div class="friends-chat-item">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="regular-text" style="margin-left: 8px">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 right-pos-margin" alt="">
                            <input type="hidden" value="">
                        </div>
                        <?php
                            $user_relation = new UserRelation();
                            $friends = $user_relation->get_friends($current_user_id);
    
                            
                            foreach($friends as $friend) {
                                ChatComponent::generate_chat_page_friend_contact($current_user_id, $friend);
                            }
                        ?>
                    </div>
                </div>
                <div id="no-discussion-yet">
                    <div class="flex-justify-column" style="text-align: center">
                        <h2>You don't have a message selected</h2>
                        <p class="regular-text">Choose one from your existing messages, or start a new one.</p>
                        <a href="" class="new-message-button">New Message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>