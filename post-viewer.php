
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, User, Comment, Like};
use layouts\post\Post as Post_View;

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

require_once "functions/sanitize_id.php";

$pid = null;
if(isset($_GET["pid"])) {
    $pid = sanitize_id($_GET["pid"]);
} else {
    // Redirect user to post not found page
    Redirect::to(Config::get("root/path") . "page_parts/errors/404.php");
}

$current_user_id = $user->getPropertyValue("id");

$post = new Post();
$post->fetchPost($pid);
$post_owner_id = $post->get_property("post_owner");

$post_owner = new User();
$post_owner->fetchUser("id", $post_owner_id);
$post_owner_picture = $root . (empty($post_owner->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $post_owner->getPropertyValue("picture"));
$post_owner_fullname = $post_owner->getPropertyValue("firstname") . " " . $post_owner->getPropertyValue("lastname");
$post_owner_username = $post_owner->getPropertyValue("username");
$post_date = $post->get_property("post_date");
$post_date = date("F d \a\\t Y h:i A",strtotime($post_date));
$post_visibility_image_path = "";
if($post->get_property("post_visibility") == 1) {
    $post_visibility_image_path = "public";
} else if($post->get_property("post_visibility") == 2) {
    $post_visibility_image_path = "friends";
} else {
    $post_visibility_image_path = "lock";
}
$post_text_data = $post->get_property("text_content");

// Get images
$poster_image_directory = $post->get_property("picture_media");

$root = Config::get("root/path");
$project_name = Config::get("root/project_name");
$project_path = $_SERVER['DOCUMENT_ROOT'] . "/" . $project_name . "/";

$post_images_dir = $project_path . $post->get_property("picture_media");

$post_text_content = htmlspecialchars_decode($post->get_property("text_content"));

$images = "";
if(is_dir($post_images_dir)) {
    if(is_dir_empty($post_images_dir) == false) {
        $fileSystemIterator = new \FilesystemIterator($post_images_dir);
        foreach ($fileSystemIterator as $fileInfo){
            $fileName = $root . $poster_image_directory . $fileInfo->getFilename();
            $images .= "<input type='hidden' value='$fileName' class='post-asset-image'>";
        }
        $images .= "<input type='hidden' value='0' class='current-asset-image'>";
    }
}

$post_meta_like = <<<LM
<div class="no-display post-meta-likes post-meta"><span class="meta-count">0</span>Likes</div>
LM;
$post_meta_comment = <<<CM
<div class="no-display post-meta-comments post-meta"><span class="meta-count">0</span>Comments</div>
CM;
$post_meta_share = <<<SM
<div class="no-display post-meta-shares post-meta"><span class="meta-count">0</span>Shares</div>
SM;

$ce = $se = "";

// Comment meta
$pmc = count(Comment::fetch_post_comments($pid));
if($pmc == 0) {
    $ce = "no-display";
}

// Like
$like_manager = new Like();
$likes_count = count($like_manager->get_post_users_likes_by_post($pid));
$lk = "like-black-filled.png";
if($likes_count == 0) {
    $likes_count = "";
    $lk = "like-black.png";
}

$like_text_state = "Like";
$like_manager->setData(array(
    "user_id"=>$user->getPropertyValue("id"),
    "post_id"=>$pid
));
$like_image = "like-black.png";
if($like_manager->exists()) {
    $like_text_state = "Liked";
    $like_image = "like-black-filled.png";
}

// Share
$shares = Post::get_post_share_numbers($pid);
if($shares == 0) {
    $se = "no-display";
}

$current_user_picture = $root . (empty($user->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));

function is_dir_empty($dir) {
    return (count(glob("$dir/*")) === 0); // empty
}

function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

/*if(!is_dir($poster_image_directory)) {
    exit('Invalid diretory path');
}

/*$files = array();
foreach (scandir($directory) as $file) {
    if ($file !== '.' && $file !== '..') {
        $files[] = $file;
    }
}*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>V01D47</title>
<link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico' />
<link rel="stylesheet" href="public/css/header.css">
<link rel="stylesheet" href="public/css/global.css">
<link rel="stylesheet" href="public/css/post.css">
<link rel="stylesheet" href="public/css/post-viewer.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="public/javascript/config.js" defer></script>
<script src="public/javascript/post.js" defer></script>
<script src="public/javascript/post-viewer.js" defer></script>
<script src="public/javascript/global.js" defer></script>
</head>
<body>
<?php include_once "page_parts/basic/header.php"; ?>
<main>
    <div class="notification-bottom-container">
        <p class="notification-bottom-sentence">THIS IS TEST</p>
    </div>
    <?php // Redirect to Post not found page ?>
    <div id="post-viewer" class="post-item">
        <div class="images">
            <?php echo $images; ?>
        </div>
        <div id="post-assets-container" class="relative">
            <div id="asset-wrapper">
                <img src="" class="asset-image" alt="">
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
                    <div class="poster-image-container">
                        <a href=""><img src="<?php echo $post_owner_picture ?>" class="poster-image" alt=""></a>
                    </div>
                    <div style="margin-left: 6px;">
                        <a href="http://127.0.0.1/CHAT/profile.php?username=<?php echo $post_owner_username; ?>" class="post-owner-name"><?php echo $post_owner_fullname; ?> -@<?php echo $post_owner_username ?></a>
                        <div class="row-v-flex">
                            <p class="regular-text"><a href="" class="post-date"><?php echo $post_date; ?></a> <span style="font-size: 14px; color: rgb(78, 78, 78);">.</span></p>
                            <img src="public/assets/images/icons/<?php echo $post_visibility_image_path; ?>.png" class="image-style-8" alt="" style="margin-left: 8px">
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
                    <span class="hidden-post-text no-display"><?php echo $post_text_data; ?></span>
                    <span class="post-text"><?php echo $post_text_data; ?></span>
                    <div class="collapse-text">See more</div>
                </div>
            </div>
            <!-- post reactions number, comments and shares -->
            <div style="margin-top: 14px" class="flex">
                <div class="pointer list-liked-people row-v-flex">
                    <img src="public/assets/images/icons/like-black.png" class="like-friends-btn reaction-button-image" alt="">
                    <p class="regular-text-style-2 bold like-counter"><?php echo $likes_count; ?></p>
                </div>
                <div class="right-pos-margin flex">
                    <div style="margin-right: 6px" class="pointer hover-underline <?php echo $ce ?> noc-container"><span class="num-of-comments regular-text"><?php echo $pmc ?></span>Comments</div>
                    <div class="pointer hover-underline nos-container <?php echo $se ?>"><span class="num-of-shares regular-text"><?php echo $shares; ?></span>shares</div>
                </div>
            </div>
            <div class="reaction-box">
                <div class="pointer like row-v-flex reaction-button">
                    <img src="public/assets/images/icons/<?php echo $like_image; ?>" class="reaction-button-image like-button-image" alt="">
                    <a class="regular-text-style-2 bold like-text-state"><?php echo $like_text_state ?></a>
                </div>
                <div class="pointer row-v-flex reaction-button comment" style="flex: 4">
                    <img src="public/assets/images/icons/black-comment.png" class="reaction-button-image comment-button-image" alt="">
                    <a class="regular-text-style-2 bold">Comment</a>
                </div>
                <div class="relative share-button-container">
                    <div class="pointer row-v-flex reaction-button share">
                        <img src="public/assets/images/icons/reply-black.png" class="reaction-button-image share-button-image" alt="">
                        <a class="regular-text-style-2 bold">Share</a>
                    </div>
                    <div class="share-animation-container flex-row-column">
                        <div class="share-animation-outer-circle-container">
                            
                        </div>
                        <div class="share-animation-inner-circle-container">
                            
                        </div>
                        <div class="animation-hand">

                        </div>
                    </div>
                </div>
            </div>
            <div class="comment-section">
                <div class="comment-block owner_type_section">
                    <div class="comment_owner_picture_container">
                        <img src="<?php echo $current_user_picture ?>" class="comment_owner_picture" alt="TT">
                    </div>
                    <div class="comment-input-form-wrapper">
                        <form action="" method="POST" class="comment-form relative">
                            <input type="text" name="comment" placeholder="Write a comment .." autocomplete="off" class="comment-input-style comment-inp">
                        </form>
                    </div>
                </div>
                <div id="pcomments">
                    <?php 
                        $comments_components = '';
                        foreach(Comment::fetch_post_comments($pid) as $comment) {
                            $cm = new Comment();
                            $cm->fetch_comment($comment->id);
            
                            echo Post_View::generate_comment($cm, $current_user_id);
                        }        
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>