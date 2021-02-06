
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\{Post, UserRelation, Follow};
    use view\post\Post as Post_View;
    use view\master_right\Right as MasterRightComponents;

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
    $journal_posts = Post::fetch_journal_posts($current_user_id);
    // Let's randomly sort array for now
    shuffle($journal_posts);
    /*usort($journal_posts, 'post_date_latest_sort');

    function post_date_latest_sort($post1, $post2) {
        return $post1->get_property('post_date') == $post2->get_property('post_date') ? 0 : ($post1->get_property('post_date') > $post2->get_property('post_date')) ? -1 : 1;
    }*/
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
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/create-post-style.css">
    <link rel="stylesheet" href="styles/master-left-panel.css">
    <link rel="stylesheet" href="styles/master-right-contacts.css">
    <link rel="stylesheet" href="styles/post.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="javascript/config.js" defer></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/index.js" defer></script>
    <script src="javascript/global.js" defer></script>
    <script src="javascript/master-right.js" defer></script>
    <script src="javascript/post.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <div id="global-container" class="relative">
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
                <?php include_once "components/basic/create-post.php"; ?>
                <?php //include_once "components/post/post_viewer.php"; ?>
                <div class="post-item">
                    <div class="timeline-post image-post">
                        <div class="post-header flex-space">
                            <div class="post-header-without-more-button">
                                <img src="$post_owner_picture" class="image-style-7 post-owner-picture" alt="">
                                <div class="post-header-textual-section">
                                    <a href="$post_owner_profile" class="post-owner-name">$post_owner_name</a>
                                    <div class="row-v-flex">
                                        <p class="regular-text"><a href="" class="post-date">$post_date</a> <span style="font-size: 8px">.</span></p>
                                        <img src="assets/images/icons/public-white.png" class="image-style-8" alt="" style="margin-left: 8px">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="" class="button-style-6 dotted-more-back"></a>
                            </div>
                        </div>
                        <p class="post-text">
                            This is text
                        </p>
                        <div class="media-container">
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                            </div>
                        </div>
                        <div class="react-on-opost-buttons-container">
                            <a href="" class="white-like-back post-bottom-button">Like</a>
                            <a href="" class="white-comment-back post-bottom-button">Comment</a>
                            <a href="" class="white-like-back post-bottom-button">Like</a>
                        </div>
                        <div class="comment-section">
                            <div class="comment-block">
                                <div>
                                    <img src="assets/images/read.png" class="image-style-9" alt="">
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
                                    <img src="" class="image-style-9" alt="">
                                </div>
                                <div class="comment-input-form-wrapper">
                                    <form action="" method="POST" class="comment-form relative">
                                        <input type="text" name="comment" placeholder="Write a comment .." class="comment-style">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="pid" value="">
                </div>
                <div id="posts-container">
                    <?php
                        foreach($journal_posts as $post) {
                            $post_view = new Post_View();

                            echo $post_view->generate_timeline_post($post);
                        }
                    ?>
                </div>
            </div>
            <?php include_once "components/basic/master-right.php" ?>
        </div>
    </main>
</body>
</html>