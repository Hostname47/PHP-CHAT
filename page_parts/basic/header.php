
<?php
    use classes\{Config, Token, Session, Common, Redirect};

    if(isset($_POST["logout"])) {
        if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            Redirect::to("login/login.php");
        }
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
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="explore-button">Explore</a>
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
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2 button-with-suboption notification-button"></a>
                    <div class="sub-label">
                        <!-- "Notifications" will appear when user hover over the abov link button -->
                    </div>
                    <div class="sub-options-container sub-options-container-style-1">
                        <h2 class="title-style-1">Notifications</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="" class="sub-option">
                                <div class="notif-option-item">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/logos/logo512.png"; ?>" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="notification-content-container">
                                        <p class="notif-content"><span class="action-doer">Mouad Nassri</span> <span class="notif-action">commented</span> <span> in your profile picture</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                </div>
                            </a>
                            <a href="" class="sub-option">
                                <div class="notif-option-item">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/logos/logo512.png"; ?>" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="notification-content-container">
                                        <p class="notif-content"><span class="action-doer">Mouad Nassri</span> <span class="notif-action">commented</span> <span> in your profile picture</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2 button-with-suboption messages-button"></a>
                    <div class="sub-options-container sub-options-container-style-1">
                        <h2 class="title-style-1">Messages</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="" class="sub-option">
                                <div class="message-option-item">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/logos/logo512.png"; ?>" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="message-sender">Mouad Nassri</p>
                                        <p class="message-content"><span class="message-sender"></span><span class="mesage-text"> Hello grotto, long time no see !</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/icons/sent.png"; ?>" class="message-state-sign" alt="message state icon">
                                    </div>
                                </div>
                            </a>
                            <a href="" class="sub-option">
                                <div class="message-option-item">
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/logos/logo512.png"; ?>" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="message-sender">Loup Garou</p>
                                        <p class="message-content"><span class="message-sender"></span><span class="mesage-text">Salam, my name is grotto, do you remember me ?</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                    <div>
                                        <img src="<?php echo Config::get("root/path") . "public/assets/images/icons/received.png"; ?>" class="message-state-sign" alt="message state icon">
                                    </div>
                                </div>
                            </a>
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