
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{User, Post, Follow, UserRelation};
use view\post\{Post as Post_view};
// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

$username = isset($_GET["username"]) ? trim(htmlspecialchars($_GET["username"])) : '';

if(!($user->getPropertyValue("username") == $username) && $username != "") {
    $fetched_user = new User();
    // If there's now user with the given username in the link it will redirect the user to 404 error page
    if($fetched_user->fetchUser("username", $username)) {
        $posts = Post::get("post_owner", $fetched_user->getPropertyValue("id"));
    } else {
        Redirect::to("errors/404.php");
    }
} else {
    $fetched_user = $user;
    $posts = Post::get("post_owner", $user->getPropertyValue("id"));
}

$profile_user_id = $fetched_user->getPropertyValue("id");

if(isset($_POST["save-profile-edites"])) {
    if(Token::check(Common::getInput($_POST, "save_token"), "saveEdits")) {
        $validate = new Validation();

        $validate->check($_POST, array(
            "firstname"=>array(
                "name"=>"Firstname",
                "required"=>true,
                "min"=>6,
                "max"=>40
            ),
            "lastname"=>array(
                "name"=>"Lastname",
                "required"=>true,
                "min"=>6,
                "max"=>40
            ),
            "private"=>array(
                "name"=>"Profile (public/private)",
                "range"=>array(-1, 1)
            )
        ));
        
        $validate->check($_FILES, array(
            "picture"=>array(
                "name"=>"Picture",
                "image"=>"image"
            ),
            "cover"=>array(
                "name"=>"Cover",
                "image"=>"image"
            )
        ));

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

if(isset($_POST["logout"])) {
    if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
        $user->logout();
        Redirect::to("login/login.php");
    }
}

usort($posts, 'post_date_latest_sort');

function post_date_latest_sort($post1, $post2) {
    return $post1->get_property('post_date') == $post2->get_property('post_date') ? 0 : ($post1->get_property('post_date') > $post2->get_property('post_date')) ? -1 : 1;
}

$posts_number = Post::get_posts_number($profile_user_id);
$followers_number = Follow::get_user_followers_number($profile_user_id);
$followed_number = Follow::get_followed_users_number($profile_user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - <?php echo $fetched_user->getPropertyValue("username"); ?></title>
    <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/create-post-style.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/post.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="javascript/config.js" defer></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/profile.js" defer></script>
    <script src="javascript/global.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main class="relative">
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
                    <div class="relative" style="border-radius: 50%">
                        <img src="<?php echo $fetched_user->getPropertyValue("picture"); ?>" class="profile-picture" alt="">
                        <img src="" class="profile-picture shadow-profile-picture absolute" alt="">
                    </div>
                    <div class="viewer">
                        <div class="relative">
                            <a href="" class="close-viewer-style close-viewer"></a>
                        </div>
                        <img src="" class="profile-picture-preview" alt="User's name Profile picture">
                    </div>
                </div>
            </div>
            <div id="name-and-username-container">
                <h1 class="title-style-3"><?php echo $fetched_user->getPropertyValue("firstname") . " " . $fetched_user->getPropertyValue("lastname"); ?></h1>
                <p class="regular-text-style-1">@<?php echo $fetched_user->getPropertyValue("username"); ?></p>
            </div>
            <?php
                if($fetched_user->getPropertyValue("id") == $user->getPropertyValue("id")) {
                    include_once "components/profile/owner-profile-header.php";
                } else {
                    include_once "components/profile/contact-header.php";
                }
            ?>
        </section>
        <section id="second-section">
            <div>
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
                </div>
                <div class="user-info-section">
                    <h2 class="title-style-4">About</h2>
                    <p class="calendar-icon regular-text-style-2">Member since <?php echo date("F Y", strtotime($fetched_user->getPropertyValue("joined"))) ?></p>
                </div>
                <div class="user-info-section">
                    <div class="flex-space">
                        <h2 class="title-style-4">Media</h2>
                        <a href="" class="link-style-1">Show all</a>
                    </div>
                    <div>
                        <div class="user-info-square-shapes-container">
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                        </div>
                        <div class="user-info-square-shapes-container">
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                            <div class="user-info-square-shape">
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="profile-posts-section">
                <?php include_once "components/basic/create-post.php"; ?>
                <div id="posts-container">
                    <?php
                        foreach($posts as $post) {
                            $post_view = new Post_view();

                            echo $post_view->generate_timeline_post($post);
                        }
                    ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>