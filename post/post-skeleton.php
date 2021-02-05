
<?php

require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, UserRelation, Follow};
use view\post\Post as Post_View;
use view\master_right\Right as MasterRightComponents;

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

require_once "../functions/sanitize_id.php";

$pid = null;
if(isset($_GET["pid"])) {
    $pid = sanitize_id($_GET["pid"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>V01D47</title>
<link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicons/favicon.ico' />
<link rel="stylesheet" href="../styles/header.css">
<link rel="stylesheet" href="../styles/global.css">
<link rel="stylesheet" href="../styles/post.css">
<link rel="stylesheet" href="../styles/post-viewer.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../javascript/config.js" defer></script>
<script src="../javascript/header.js" defer></script>
<script src="../javascript/post-viewer.js" defer></script>
<script src="../javascript/global.js" defer></script>
</head>
<body>
<?php include_once "../components/basic/header.php"; ?>
<main>
    <?php if($pid == null) { ?>
        <p>Something went wrong</p>
    <?php } else { ?>
    <div id="post-viewer">
        <div id="post-assets-container" class="relative">
            <div id="asset-wrapper">
                <img src="../assets/images/read.png" class="asset-image" alt="">
            </div>

            <div class="asset-back asset-move-button">
            </div>

            <div class="asset-next asset-move-button">
            </div>

            <div class="exit-button">
            </div>
        </div>
        <div id="post-info" class="relative">
            <!-- post owner header -->
            <div class="flex">
                <div class="flex">
                    <a href=""><img src="../assets/images/read.png" class="poster-image" alt=""></a>
                    <div style="margin-left: 6px;">
                        <a href="http://127.0.0.1/CHAT/profile.php?username=shreda" class="post-owner-name">Master Shreda -@shreda</a>
                        <div class="row-v-flex">
                            <p class="regular-text"><a href="" class="post-date">March 21, 2013</a> <span style="font-size: 14px; color: rgb(78, 78, 78);">.</span></p>
                            <img src="../assets/images/icons/public.png" class="image-style-8" alt="" style="margin-left: 8px">
                        </div>
                    </div>
                </div>
                <div class="right-pos-margin relative">
                    <a href="" class="black-more-button button-with-suboption"></a>
                    <div class="sub-options-container sub-options-container-style-2">
                        <div class="options-container-style-1">
                            <div class="sub-option-style-2 save-post-button">
                                <p for="" class="regular-text margintb4 padding-back-style-1 save-back">Save post</p>
                            </div>
                            <div class="sub-option-style-2 download-post-button">
                                <p for="" class="regular-text margintb4 padding-back-style-1 download-back">Download</p>
                            </div>
                            <div class="sub-option-style-2 full-screen-post">
                                <p for="" class="regular-text margintb4 padding-back-style-1 full-screen-black-back">Enter full-screen</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- post text -->
            <div>
                <div style="margin-top: 8px">
                    <span class="hidden-post-text no-display">It works for me locally under Chrome, FF, IE 7+, Safari, and Opera. The example shows three sections: Head, Sidebar, and Content. Both Sidebar and Content fill the page horizontally (minus a 25px bottom margin).</span>
                    <span class="post-text">It works for me locally under Chrome, FF, IE 7+, Safari, and Opera. The example shows three sections: Head, Sidebar, and Content. Both Sidebar and Content fill the page horizontally (minus a 25px bottom margin).</span>
                    <div class="collapse-text">See more</div>
                </div>
            </div>
            <!-- post reactions number, comments and shares -->
            <div style="margin-top: 14px" class="flex">
                <div class="pointer list-liked-people row-v-flex">
                    <img src="../assets/images/icons/like-black.png" class="like-button-image" alt="">
                    <p class="regular-text-style-2 bold">128</p>
                </div>
                <div class="right-pos-margin flex">
                    <div style="margin-right: 6px" class="pointer hover-underline"><span class="num-of-comments regular-text">1</span>Comments</div>
                    <div class="pointer hover-underline"><span class="num-of-shares regular-text">14</span>shares</div>
                </div>
            </div>
            <div class="reaction-box">
                <div class="pointer like-button row-v-flex reaction-button">
                    <img src="../assets/images/icons/like-black.png" class="like-button-image" alt="">
                    <a class="regular-text-style-2 bold">Like</a>
                </div>
                <div class="pointer row-v-flex reaction-button comment-button" style="flex: 4">
                    <img src="../assets/images/icons/black-comment.png" class="like-button-image" alt="">
                    <a class="regular-text-style-2 bold">Comment</a>
                </div>
                <div class="pointer row-v-flex reaction-button share-button">
                    <img src="../assets/images/icons/reply-black.png" class="like-button-image" alt="">
                    <a class="regular-text-style-2 bold">Share</a>
                </div>
            </div>
            <div class="comment-section">
                <div class="comment-block">
                    <div>
                        <img src="../assets/images/read.png" class="image-style-9" alt="">
                    </div>
                    <div>
                        <div class="comment-wrapper">
                            <a href="" class="comment-owner">grotto</a>
                            <p class="comment-text">This is a comment for testing stuff</p>
                        </div>
                        <div class="row-v-flex underneath-comment-buttons-container">
                            <a href="" class="link-style-3">like</a>
                            <a href="" class="link-style-3">reply</a>
                            <div style="margin-left: 6px">
                                <p class="regular-text-style-2"> . <span class="time-of-comment">5min</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment-block">
                    <div>
                        <img src="../assets/images/read.png" class="image-style-9" alt="">
                    </div>
                    <div class="comment-input-form-wrapper">
                        <form action="" method="POST" class="comment-form relative">
                            <input type="text" name="comment" placeholder="Write a comment .." class="comment-style">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</main>
</body>
</html>