
<?php
    use classes\{Config, Token};
?>
<header>
    <div id="top-header">
        <div id="header-logo-container">
            <!-- height of <a> should be like img height -->
            <a href="" style="height: 28px"><img src="<?php echo Config::get("root/path");?>assets/images/logos/large.png" alt="Logo" id="header-logo"></a>
        </div>
        <div class="inline-logo-separator">〡</div>
        <div class="row-v-flex">
            <form action="" method="post" id="header-search-form">
                <input type="text" name="resource-search-field" class="input-text-style-1 search-back" placeholder="Search for friends, posts, events ..">
                <input type="submit" value="search" name="resource-search-button" class="search-button-style-1">
            </form>
        </div>
        <div id="global-header-strip-container">
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="home-button">Home</a>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="explore-button">Explore</a>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="live-button">Live</a>
                </div>
            </div>
            <div class="menu-items-separator">〡</div>
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2 button-with-suboption" id="notification-button"></a>
                    <div class="sub-label">
                        <!-- "Notifications" will appear when user hover over the abov link button -->
                    </div>
                    <div class="sub-options-container">
                        <h2 class="title-style-1">Notifications</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="" class="sub-option">
                                <div class="notif-option-item">
                                    <div>
                                        <img src="assets/images/logos/logo512.png" class="image-style-1" alt="user's profile picture">
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
                                        <img src="assets/images/logos/logo512.png" class="image-style-1" alt="user's profile picture">
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
                    <a href="" class="menu-button-style-2 button-with-suboption" id="messages-button"></a>
                    <div class="sub-options-container">
                        <h2 class="title-style-1">Messages</h2>
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="" class="sub-option">
                                <div class="message-option-item">
                                    <div>
                                        <img src="assets/images/logos/logo512.png" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="message-sender">Mouad Nassri</p>
                                        <p class="message-content"><span class="message-sender"></span><span class="mesage-text"> Hello grotto, long time no see !</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                    <div>
                                        <img src="assets/images/icons/sent.png" class="message-state-sign" alt="message state icon">
                                    </div>
                                </div>
                            </a>
                            <a href="" class="sub-option">
                                <div class="message-option-item">
                                    <div>
                                        <img src="assets/images/logos/logo512.png" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="message-sender">Loup Garou</p>
                                        <p class="message-content"><span class="message-sender"></span><span class="mesage-text">Salam, my name is grotto, do you remember me ?</span></p>
                                        <p class="notif-date">40 minutes ago</p>
                                    </div>
                                    <div>
                                        <img src="assets/images/icons/received.png" class="message-state-sign" alt="message state icon">
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
                    <div class="sub-options-container">
                        <!-- When this link get pressed you need to redirect the user to the notification post -->
                        <div class="options-container">
                            <a href="profile.php" class="sub-option">
                                <div class="message-option-item" style="align-items: center">
                                    <div>
                                        <img src="assets/images/icons/user.png" class="image-style-1" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p class="account-user"><?php echo $user->getPropertyValue("username"); ?></p>
                                        <p class="message-content">See your profile</p>
                                    </div>
                                </div>
                            </a>
                            <div class="options-separator-style-1"></div>
                            <a href="" class="sub-option">
                                <div class="row-v-flex">
                                    <div>
                                        <img src="assets/images/icons/settings.png" class="image-style-2" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p style="margin: 4px">Settings</p>
                                    </div>
                                </div>
                            </a>
                            <button name="logout" type="submit" form="logout-form" class="sub-option logout-button">
                                <div class="row-v-flex">
                                    <div>
                                        <img src="assets/images/icons/logout.png" class="image-style-2" alt="user's profile picture">
                                    </div>
                                    <div class="message-content-container">
                                        <p style="margin: 4px">Logout</p>
                                    </div>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="logout-form">
                                    <input type="hidden" name="token_logout" value="<?php echo Token::generate("logout"); ?>">
                                </form>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>