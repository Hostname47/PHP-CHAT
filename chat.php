
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, UserRelation, Follow, Message, User};
use layouts\post\Post as Post_View;
use layouts\chat\ChatComponent;
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47</title>
    <link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/chat.css">
    <link rel="stylesheet" href="public/css/master-left-panel.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="public/javascript/config.js" defer></script>
    <script src="public/javascript/global.js" defer></script>
    <script src="public/javascript/chat.js" defer></script>
</head>
<body>
<?php include_once "page_parts/basic/header.php"; ?>
<main>
    <div id="global-container">
        <?php include_once "page_parts/basic/master-left.php"; ?>
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
                        <div class="section-title">Discussions: </div>

                        <div style="margin-left: auto">
                            <a href="" class="menu-button-style-3 refresh-button refresh-discussion"></a>
                        </div>
                    </div>
                    <div id="friend-chat-discussions-container">
                        <?php

                            $discussions = Message::get_discussions($current_user_id);
                            $temp = array();
                            $result = array();
                            
                            /* 
                                Now let's take the case when we have user with id: 5 send a message to user with id: 17
                                AND ALSO user with id: 17 send a message to user with id: 5. What we need to do in this case is
                                include only the last message for exemple if user: 17 send the last message we take this message
                                only and exclude message sent by user with id: 5
                                get_discussions function will return both sides but we need to adjust it to our needs
                            */
                            foreach($discussions as $discussion) {

                                $current_disc = array(
                                    "sender"=>$discussion->message_receiver,
                                    "receiver"=>$discussion->message_creator
                                );

                                if(in_array($current_disc, $temp)) {
                                    continue; 
                                }

                                $temp[] = array(
                                    "sender"=>$discussion->message_creator,
                                    "receiver"=>$discussion->message_receiver
                                );
                                $result[] = $discussion;
                            }

                            foreach($result as $discussion) {
                                $chat_comp = new ChatComponent();

                                echo $chat_comp->generate_discussion($current_user_id, $discussion);
                            }
                        ?>
                    </div>
                    <div class="friends-chat-search-container" style="border-right: none;">
                        <div class="section-title">Friends: </div>
                        <input type="text" class="chat-input-style friend-search-input search-back white-search" placeholder="Search for a friend (username) ..">
                    </div>
                    <div id="friends-chat-container" class="relative">
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