<?php

    use classes\Config;
    use models\{Post, Follow, UserRelation};

    $current_user_id = $user->getPropertyValue("id");

    $user_profile = Config::get("root/path") . (empty($user->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));
    if(empty($user->getPropertyValue("cover"))) {
        $user_cover = "";
    } else {
        $user_cover = Config::get("root/path") . $user->getPropertyValue("cover");
    }
    $posts_number = Post::get_posts_number($current_user_id);
    $followers_number = Follow::get_user_followers_number($current_user_id);
    $followed_number = Follow::get_followed_users_number($current_user_id);
    $friends_number = UserRelation::get_friends_number($current_user_id);
    $user_profile_path = Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");

?>

<div id="master-left">
    <div class="flex-space">
        <h3 class="title-style-2">Home</h3>
        <div class="flex">
            <a href="<?php echo Config::get("root/path") . "settings.php"; ?>" class="menu-button-style-3 settings-back" id="go-to-settings"></a>
            <a href="<?php echo Config::get("root/path") . "chat.php"; ?>" class="menu-button-style-3 messages-button"></a>
            <a href="<?php echo Config::get("root/path") . "search.php"; ?>" class="menu-button-style-3 search-background go-to-search"></a>
        </div>
    </div>
    <div>
        <div>
            <a href="<?php echo Config::get("root/path") . "profile.php?user=" . $user->getPropertyValue("username"); ?>" class="no-underline menu-item-style-1 row-v-flex">
                <div class="profile-owner-picture-left-panel-container">
                    <img src="<?php echo $user_profile; ?>" class="profile-owner-picture-left-panel" alt="">
                </div>
                <p class="label-style-3"><?php echo $user->getPropertyValue("username"); ?></p>
            </a>
            <div id="master-left-container">
                <p class="regular-text green-text" style="margin: 6px 0 4px 6px">Menu</p>
                <a href="<?php echo Config::get("root/path") . "index.php" ?>" class="no-underline menu-item-style-2 row-v-flex">
                    <div class="image-style-2 flex-row-column">
                        <img src="public/assets/images/icons/home-w.png" class="image-style-5" alt="">
                    </div>
                    <p class="label-style-3">Home</p>
                </a>
                <a href="<?php echo Config::get("root/path") . "notifications.php" ?>" class="no-underline menu-item-style-2 row-v-flex">
                    <div class="image-style-2 flex-row-column">
                        <img src="public/assets/images/icons/notification.png" class="image-style-5" alt="">
                    </div>
                    <p class="label-style-3">Notifications</p>
                </a>
                <a href="<?php echo Config::get("root/path") . "chat.php" ?>" class="no-underline menu-item-style-2 row-v-flex">
                    <div class="image-style-2 flex-row-column">
                        <img src="public/assets/images/icons/messages.png" class="image-style-5" alt="">
                    </div>
                    <p class="label-style-3">Messages</p>
                </a>
                <a href="<?php echo Config::get("root/path") . "notifications.php" ?>" class="no-underline menu-item-style-2 row-v-flex">
                    <div class="image-style-2 flex-row-column">
                        <img src="public/assets/images/icons/page.png" class="image-style-5" alt="">
                    </div>
                    <p class="label-style-3">Pages</p>
                </a>
                <a href="<?php echo Config::get("root/path") . "notifications.php" ?>" class="no-underline menu-item-style-2 row-v-flex">
                    <div class="image-style-2 flex-row-column">
                        <img src="public/assets/images/icons/group-w.png" class="image-style-5" alt="">
                    </div>
                    <p class="label-style-3">Groups</p>
                </a>
                <p class="regular-text green-text" style="margin: 6px 0 8px 6px">Profile</p>
                <div id="menu-profile-container" class="relative">
                    <div class="absolute header-profile-edit-container">
                        <a href="" class="button-style-4 header-profile-edit-button">Edit</a>
                    </div>
                    <div class="cover-container">
                        <img src="<?php echo $user_cover; ?>" class="cover-photo" alt="">
                    </div>
                    <a href="<?php echo $user_profile_path; ?>" class="picture-container absolute">
                        <img src="<?php echo $user_profile; ?>" class="picture-photo" alt="">
                    </a>
                    <div class="header-profile-name-container">
                        <a href="<?php echo $user_profile_path; ?>" class="no-underline"><h1 class="header-profile-fullname"><?php echo $user->getPropertyValue("firstname") . " " . $user->getPropertyValue("lastname"); ?></h1></a>
                        <p class="header-profile-username">@<?php echo $user->getPropertyValue("username"); ?></p>
                    </div>
                    <div class="user-info-section row-v-flex">
                        <a href="" class="user-info-section-link">
                            <div>
                                <h2 class="title-style-4"><?php echo $posts_number; ?></h2>
                                <p class="regular-text-style-2">Posts</p>
                            </div>
                        </a>
                        <a href="" class="user-info-section-link">
                            <div>
                                <h2 class="title-style-4"><?php echo $followers_number; ?></h2>
                                <p class="regular-text-style-2">Followers</p>
                            </div>
                        </a>
                        <a href="" class="user-info-section-link">
                            <div>
                                <h2 class="title-style-4"><?php echo $followed_number; ?></h2>
                                <p class="regular-text-style-2">Following</p>
                            </div>
                        </a>
                        <a href="" class="user-info-section-link">
                            <div>
                                <h2 class="title-style-4"><?php echo $friends_number; ?></h2>
                                <p class="regular-text-style-2">Friends</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>