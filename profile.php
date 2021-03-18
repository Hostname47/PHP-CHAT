
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{User, Post, Follow, UserRelation};
use layouts\post\{Post as Post_view};
// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

if(isset($_POST["save-profile-edites"])) {
    if(Token::check(Common::getInput($_POST, "save_token"), "saveEdits")) {
        $validate = new Validation();

        $validate->check($_POST, array(
            "firstname"=>array(
                "name"=>"Firstname",
                "required"=>true,
                "min"=>4,
                "max"=>40
            ),
            "lastname"=>array(
                "name"=>"Lastname",
                "required"=>true,
                "min"=>4,
                "max"=>40
            ),
            "private"=>array(
                "name"=>"Profile (public/private)",
                "range"=>array(-1, 1)
            )
        ));
        
        if(!empty($_FILES["picture"]["name"])) {
            $validate->check($_FILES, array(
                "picture"=>array(
                    "name"=>"Picture",
                    "image"=>"image"
                )
            ));
        }

        if(!empty($_FILES["cover"]["name"])) {
            $validate->check($_FILES, array(
                "cover"=>array(
                    "name"=>"Cover",
                    "image"=>"image"
                )
            ));
        }

        if($validate->passed()) {
            // Set textual data
            $user->setPropertyValue("firstname", $_POST["firstname"]);
            $user->setPropertyValue("lastname", $_POST["lastname"]);
            $user->setPropertyValue("bio", $_POST["bio"]);
            $user->setPropertyValue("private", $_POST["private"]);

            $profilePicturesDir = 'data/users/' . $user->getPropertyValue("username") . "/media/pictures/";
            $coversDir = 'data/users/' . $user->getPropertyValue("username") . "/media/covers/";

            // First we check if the user is changed the image
            if(!empty($_FILES["picture"]["name"])) {
                // If so we generate a unique hash to name the image
                $generatedName = Hash::unique();
                $generatedName = htmlspecialchars($generatedName);

                // Then we fetch the image type t o concatenate it with the generated name
                $file = $_FILES["picture"]["name"];
                $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);

                $targetFile = $profilePicturesDir . $generatedName . $original_extension;
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
                    $user->setPropertyValue("picture", $targetFile);
                } else {
                    $validate->addError("Sorry, there was an error uploading your profile picture.");
                }
            }

            if(!empty($_FILES["cover"]["name"])) {
                $generatedName = Hash::unique();
                $generatedName = htmlspecialchars($generatedName);
                
                $file = $_FILES["cover"]["name"];
                $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);

                $targetFile = $coversDir . $generatedName . $original_extension;
                if (move_uploaded_file($_FILES["cover"]["tmp_name"], $targetFile)) {
                    $user->setPropertyValue("cover", $targetFile);
                } else {
                    $validate->addError("Sorry, there was an error uploading your profile picture.");
                }
            }

            $user->update();
        } else {
            foreach($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }
}

$username = isset($_GET["username"]) ? trim(htmlspecialchars($_GET["username"])) : '';

if(!($user->getPropertyValue("username") == $username) && $username != "") {
    $fetched_user = new User();
    // If there's now user with the given username in the link it will redirect the user to 404 error page
    if($fetched_user->fetchUser("username", $username)) {
        $posts = Post::get("post_owner", $fetched_user->getPropertyValue("id"));
    } else {
        Redirect::to("page_parts/errors/404.php");
    }
} else {
    $fetched_user = $user;
    $posts = Post::get("post_owner", $user->getPropertyValue("id"));
}

$profile_user_id = $fetched_user->getPropertyValue("id");
$profile_user_picture = Config::get("root/path") . (empty($fetched_user->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $fetched_user->getPropertyValue("picture"));
$bio = $fetched_user->getPropertyValue('bio');

if(isset($_POST["logout"])) {
    if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
        $user->logout();
        Redirect::to("login/login.php");
    }
}

usort($posts, 'post_date_latest_sort');

function post_date_latest_sort($post1, $post2) {
    return $post1->get_property('post_date') < $post2->get_property('post_date') ? 0 : ($post1->get_property('post_date') > $post2->get_property('post_date')) ? -1 : 1;
}

function is_dir_empty($dir) {
    return (count(glob("$dir/*")) === 0); // empty
}

$posts_number = Post::get_posts_number($profile_user_id);
$followers_number = Follow::get_user_followers_number($profile_user_id);
$followed_number = Follow::get_followed_users_number($profile_user_id);
$friends_number = UserRelation::get_friends_number($profile_user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - <?php echo $fetched_user->getPropertyValue("username"); ?></title>
    <link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/profile.css">
    <link rel="stylesheet" href="public/css/post.css">
    <link rel="stylesheet" href="public/css/create-post-style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="public/javascript/config.js" defer></script>
    <script src="public/javascript/profile.js" defer></script>
    <script src="public/javascript/global.js" defer></script>
    <script src="public/javascript/post.js" defer></script>
</head>
<body>
    <?php include_once "page_parts/basic/header.php"; ?>
    <main class="relative">
        <div class="post-viewer-only">
            <div class="viewer-post-wrapper">
                <img src="" class="post-view-image" alt="">
                <div class="close-view-post"></div>
            </div>
        </div>
        <section id="first-section">
            <div class="relative flex-column">
                <div>
                    <div id="cover-container">
                        <img src="<?php echo $fetched_user->getPropertyValue("cover"); ?>" class="cover-photo" alt="">
                    </div>
                    <div class="viewer">
                        <div class="relative">
                            <a href="" class="close-viewer-style close-viewer"></a>
                        </div>
                        <img src="" class="profile-cover-picture-preview" alt="User's name cover picture">
                    </div>
                </div>
                <div id="profile-picture-container">
                    <div class="relative picture-back-color" style="border-radius: 50%; overflow: hidden">
                        <div class="profile-picture-cnt">
                            <img src="<?php echo $profile_user_picture; ?>" class="profile-picture" alt="">
                        </div>
                        <img src="" class="profile-picture shadow-profile-picture absolute" alt="">
                    </div>
                    <div class="viewer">
                        <div class="relative">
                            <a href="" class="close-viewer-style close-viewer"></a>
                        </div>
                        <div class="profile-picture-preview-container">
                            <img src="" class="profile-picture-preview" alt="User's name Profile picture">
                        </div>
                    </div>
                </div>
            </div>
            <div id="name-and-username-container">
                <h1 class="title-style-3"><?php echo $fetched_user->getPropertyValue("firstname") . " " . $fetched_user->getPropertyValue("lastname"); ?></h1>
                <p class="regular-text-style-1">@<?php echo $fetched_user->getPropertyValue("username"); ?></p>
            </div>
            <?php
                if($fetched_user->getPropertyValue("id") == $user->getPropertyValue("id")) {
                    include_once "page_parts/profile/owner-profile-header.php";
                } else {
                    include_once "page_parts/profile/contact-header.php";
                }
            ?>
        </section>
        <section id="second-section">
            <div id="second-part-left-panel">
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
                <div class="user-info-section">
                    <h2 class="title-style-4">About</h2>
                    <p class="regular-text" style="margin: 10px 0"><?php echo $bio; ?></p>
                    <p class="calendar-icon regular-text-style-2">Member since <?php echo date("F Y", strtotime($fetched_user->getPropertyValue("joined"))) ?></p>

                    <div style="margin-top: 8px">
                        <?php 

                            $metadata = $fetched_user->get_metadata();

                            foreach($metadata as $md) {
                                $label = $md->label;
                                $content = $md->content;
                                echo <<<METADATA
                                <div class="flex metadata-box">
                                    <p class="regular-text-style-1">$label</p>
                                    <p class="regular-text-style-1 right-pos-margin">$content</p>
                                </div>
METADATA;
                            }

                        ?>
                    </div>
                </div>
                <div class="user-info-section">
                    <div class="flex-space">
                        <h2 class="title-style-4">Media</h2>
                        <a href="" class="link-style-1">Show all</a>
                    </div>
                    <div class="flex flex-wrap">
                        <?php
                            $root = Config::get("root/path");
                            $project_name = Config::get("root/project_name");
                            $project_path = $_SERVER['DOCUMENT_ROOT'] . "/" . $project_name . "/";
                            
                            $post_asset = "";
                            $max_ = 8;
                            $counter = 0;

                            foreach($posts as $pst) {
                                $shared_post_images_dir = $project_path . $pst->get_property("picture_media");
                                if($counter == 8) {
                                    break;
                                }
                                
                                if($pst->get_property("picture_media") != null && is_dir_empty($shared_post_images_dir) == false) {
                                    $fileSystemIterator = new \FilesystemIterator($shared_post_images_dir);
                                    foreach ($fileSystemIterator as $fileInfo){
                                        $post_asset = $root . $pst->get_property("picture_media") . $fileInfo->getFilename();
                                        break;
                                    }
                                    $post_id = $pst->get_property("post_id");
                                    $counter++;
                                    echo <<<PPI
                                        <div class="user-media-post">
                                        <img src="$post_asset" class="user-media-post-img" alt="">
                                        <input type="hidden" class="pid" value="$post_id">
                                        </div>
PPI;
                                }
                            }
                        ?>
                        
                    </div>

                </div>
            </div>
            <div id="profile-posts-section">
                <div class="red-message">
                    <p class="red-message-text"></p>
                    <div class="delete-message-hint">
                    </div>
                </div>
                <?php
                    if($fetched_user->getPropertyValue("id") == $user->getPropertyValue("id")) {
                        include_once "page_parts/basic/post_creator.php";
                    }
                ?>
                <div id="posts-container">
                    <?php if(count($posts) == 0) { ?>
                        <div id="empty-posts-message">
                            <h2>Try to add friends, or follow them to see their posts ..</h1>
                            <p>click <a href="http://127.0.0.1/CHAT/search.php" class="link" style="color: rgb(66, 219, 66)">here</a> to go to the search page</p>
                        </div>
                    <?php } else { 
                        foreach($posts as $post) {
                            $post_view = new Post_View();

                            echo $post_view->generate_post($post, $user);
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>