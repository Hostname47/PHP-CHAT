
<?php
    use classes\{Config, Token, Session, Common, Redirect};
    use models\{UserRelation, User, Message};

    if(isset($_POST["logout"])) {
        if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            Redirect::to("login/login.php");
        }
    }

    $cuid = $user->getPropertyValue('id');

    // Fetch friendship reuqests
    $fr_senders = UserRelation::get_friendship_requests($cuid);
    $request_num_component = "";
    $friend_request_components = "";

    if(count($fr_senders) == 0) {
        $friend_request_components = <<<EMPTY_FR
            <p style="padding: 10px" class="regular-text">You don't have any friendship requests.</p>
EMPTY_FR;
    } else {
        $rnc = count($fr_senders);
        $request_num_component = <<<RNC
            <div class="num-of-requests">$rnc</div>
RNC;
        foreach($fr_senders as $sender) {

            $s = new User();
            $s->fetchUser('id', $sender->from);

            $sender_id = $s->getPropertyValue('id');
            $sender_username = $s->getPropertyValue('username');
            $sender_avatar = ($s->getPropertyValue('picture') != "") ? $s->getPropertyValue('picture') : (Config::get("root/path") . "public/assets/images/logos/logo512.png");
            $request_life = $sender->since;

            $request_life = '';

            // Get message date by substracting current date with the date of message
            $now = strtotime(date("Y/m/d h:i:s"));
            $seconds = floor($now - strtotime($sender->since));
            
            if($seconds > 29030400) {
                $request_life = floor($seconds / 29030400) . "y";
            } else if($seconds > 2419200) {
                $request_life = floor($seconds / 604800) . "w";
            } else if($seconds > 86400) {
                $request_life = floor($seconds / 86400) . "d";
            } else if($seconds < 86400 && $seconds > 3600) {
                $request_life = floor($seconds / 3600) . "h";
            } else if($seconds < 3600 && $seconds > 60) {
                $request_life = floor($seconds / 60) . "min";
            } else {
                $request_life = $seconds . "sec";
            }

            $sender_profile = $root . "profile.php?username=" .  $s->getPropertyValue('username');

            $friend_request_components .= <<<FR
    <div class="friend-request-option-item">
        <a href="$sender_profile" class="link-to-profile not-link block" style="color: black">
            <div>
                <img src="$sender_avatar" class="image-style-1" alt="user's profile picture">
            </div>
            <div class="friend-request-content-container">
                <p class="notif-content"><span class="action-doer">$sender_username</span> <span class="notif-action">sends</span> <span> you a friend request</span></p>
                <p class="notif-date">$request_life</p>
            </div>
        </a>
        <div class="right-pos-margin flex">
            <div class="right-pos-margin">
                <a href="" class="new-style-button accept-request">Accept</a>
                <a href="" class="new-style-button delete-request">Delete</a>
                <input type="hidden" value="$sender_id" class="uid">
            </div>
        </div>
    </div>
FR;
        }
    }


    // Fetch friends messages. Notice you need also to show the messages that you send to your friends and try to
    // use is_read to determine if the receiver see the message by replacing the tick image by a green one !
    $discussions = Message::get_discussions($cuid);
    $temp = array();
    $result = array();
    
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

    $messages = "";
    foreach($result as $message) {
        $friend_id = ($message->message_creator == $cuid) ? $message->message_receiver : $message->message_creator;
        $friend = new User();
        $friend->fetchUser('id', $friend_id);
        $friend_image = ($friend->getPropertyValue('picture') != "") ? $friend->getPropertyValue('picture') : (Config::get("root/path") . "public/assets/images/logos/logo512.png");
        $friend_name = $friend->getPropertyValue('firstname') . ' ' . $friend->getPropertyValue('lastname');
        $message_path = $root . 'chat.php?username=' . $friend->getPropertyValue('username');
        $msg = new Message();
        $msg->get_message('id', $message->mid);
        $msg_r = $msg->get_message_recipient_data();

        $message_text = $msg->get_property('message');

        $message_state_icon = "";
        if($message->message_creator == $cuid) {
            $message_text = "You: " . $message_text;
            
            if($msg_r->is_read) {
                $icon_path = $friend_image;
            } else {
                $icon_path = $root . "public/assets/images/icons/received.png";
            }
            $message_state_icon = <<<MSI
            <div style="margin-left: auto">
                <img src="$icon_path" class="message-state-sign" alt="message state icon">
            </div>     
MSI;
        }
        $message_lifetime = $message->message_date;

        $messages .= <<<MSG
            <a href="$message_path" class="sub-option">
                <div class="message-option-item">
                    <div>
                        <img src="$friend_image" class="image-style-1" alt="user's profile picture">
                    </div>
                    <div class="message-content-container">
                        <p class="message-sender">$friend_name</p>
                        <p class="message-content"><span class="mesage-text">$message_text</span></p>
                        <p class="notif-date">$message_lifetime</p>
                    </div>
                    $message_state_icon
                </div>
            </a>
MSG;
    }

    $setting_path = Config::get("root/path") . "settings.php";
?>
<header>
    <div id="top-header">
        <div id="header-logo-container">
            <!-- height of <a> should be like img height -->
            <a href="<?php echo Config::get("root/path");?>index.php" style="height: 28px"><img src="<?php echo Config::get("root/path");?>public/assets/images/logos/large.png" alt="Logo" id="header-logo"></a>
        </div>
        <div class="inline-logo-separator">〡</div>
        <div class="row-v-flex">
            <form action="<?php echo Config::get("root/path") . htmlspecialchars('search.php'); ?>" method="GET" id="header-search-form">
                <input type="text" name="q" value="<?php echo isset($_GET["q"]) ? trim(htmlspecialchars($_GET["q"])) : '' ?>" class="input-text-style-1 search-back black-search-back" placeholder="Search for friends, posts, events ..">
                <input type="submit" value="search" class="search-button-style-1">
            </form>
        </div>
        <div id="global-header-strip-container">
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="<?php echo Config::get("root/path");?>index.php" class="horizontal-menu-link menu-button-style-1" id="home-button">Home</a>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="<?php echo $root . "search.php" ?>" class="horizontal-menu-link menu-button-style-1" id="explore-button">Explore</a>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1 live-button">Live</a>
                </div>
            </div>
            <div class="menu-items-separator">〡</div>
            <div class="row-v-flex header-menu">

                <div class="horizontal-menu-item-wrapper">
                    <a href="<?php echo Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");?>" id="user-profile-button" class="flex-row-column">
                        <div id="header-picture-container">
                            <img id="header-picture" src="<?php echo Config::get("root/path") . ($user->getPropertyValue("picture") != "" ? $user->getPropertyValue("picture") : "public/assets/images/icons/user.png"); ?>">
                        </div>
                        <?php echo $user->getPropertyValue("username");?>
                    </a>
                    <div class="sub-label">
                        <!-- "Notifications" will appear when user hover over the abov link button -->
                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper relative">
                    <?php echo $request_num_component; ?>
                    <a href="" class="menu-button-style-2 button-with-suboption friend-request-button"></a>
                    <div class="sub-label">
                        <!-- "Notifications" will appear when user hover over the abov link button -->
                    </div>
                    <div class="sub-options-container sub-options-container-style-1">
                        <h2 class="title-style-1">Friend Requests</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <?php echo $friend_request_components ?>
                        </div>
                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2 button-with-suboption messages-button"></a>
                    <div class="sub-options-container sub-options-container-style-1">
                        <h2 class="title-style-1">Messages</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">

                            <?php echo $messages; ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-items-separator">〡</div>
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" id="user-photo-button" class="button-with-suboption"></a>
                    <div class="sub-options-container sub-options-container-style-1">
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="<?php echo Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");?>" class="sub-option">
                                <div class="message-option-item" style="align-items: center">
                                    <div class="header-menu-profile-picture-container">
                                        <img src="<?php echo Config::get("root/path") . ($user->getPropertyValue("picture") != "" ? $user->getPropertyValue("picture") : "public/assets/images/icons/user.png"); ?>" class="header-menu-profile-picture" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="account-user"><?php echo $user->getPropertyValue("username"); ?></p>
                                        <p class="message-content">See your profile</p>
                                    </div>
                                </div>
                            </a>
                            <div class="options-separator-style-1"></div>
                            <a href="<?php echo $setting_path; ?>" class="sub-option">
                                <div class="row-v-flex">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/icons/settings.png" ?>" class="image-style-2" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p style="margin: 4px">Settings</p>
                                    </div>
                                </div>
                            </a>
                            <a href="" class="sub-option">
                                <div class="row-v-flex">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/icons/log.png" ?>" class="image-style-2" alt="user activity log">
                                    </div>
                                    <div class="message-content-container">
                                        <p style="margin: 4px">Activity Log</p>
                                    </div>
                                </div>
                            </a>
                            <button name="logout" type="submit" form="logout-form" class="sub-option logout-button">
                                <div class="row-v-flex">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/icons/logout.png" ?>" class="image-style-2" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p style="margin: 4px">Logout</p>
                                    </div>
                                </div>
                            </button>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="logout-form">
                                <input type="hidden" name="token_logout" value="<?php 
                                    if(Session::exists("logout")) 
                                        echo Session::get("logout");
                                    else {
                                        echo Token::generate("logout");
                                    }
                                ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>