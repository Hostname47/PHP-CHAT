
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\{Post, UserRelation};
    use view\post\Post as Post_View;
    // DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
    // Here we check if the user is not logged in and we redirect him to login page

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }

    $welcomeMessage = '';
    if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
        $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
    }

    $journal_posts = Post::fetch_journal_posts($user->getPropertyValue("id"));
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
    <link rel="stylesheet" href="styles/post.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/index.js" defer></script>
    <script src="javascript/global.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <div id="global-container">
            <div id="master-left">
                <div class="flex-space">
                    <h3 class="title-style-2">Home</h3>
                    <div class="flex">
                        <a href="" class="menu-button-style-3 video-background" id="go-to-videos"></a>
                        <a href="" class="menu-button-style-3 messages-button"></a>
                        <a href="" class="menu-button-style-3 search-background go-to-search"></a>
                    </div>
                </div>
                <div>
                    <div>
                        <div class="menu-item-style-1 row-v-flex">
                            <img src="<?php echo Config::get("root/path") . ($user->getPropertyValue("picture") != "" ? $user->getPropertyValue("picture") : "assets/images/icons/user.png"); ?>" class="image-style-2" alt="">
                            <p class="label-style-3"><?php echo $user->getPropertyValue("username"); ?></p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/home-w.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Home</p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/notification.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Notifications</p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/messages.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Messages</p>
                        </div>
                        
                    </div>
                </div>
            </div>
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
                <div id="posts-container">
                    <?php
                        /*
                        foreach($journal_posts as $post) {
                            $post_view = new Post_view();

                            echo $post_view->generate_timeline_post($post);
                        }*/
                        foreach($journal_posts as $post) {
                            $post_view = new Post_View();

                            echo $post_view->generate_timeline_post($post);
                        }
                    ?>
                </div>
            </div>
            <div id="master-right">
                <div class="flex-space relative">
                    <h3 class="title-style-2">Contacts</h3>
                    <div>
                        <a href="" id="contact-search"></a>
                    </div>
                    <div class="absolute" id="contact-search-field-container">
                        <input type="text" id="contact-search-field" placeholder="Search by friend or group ..">
                        <a class="not-link" href=""><img src="assets/images/icons/close.png" id="close-contact-search" class="image-style-4" alt=""></a>
                    </div>
                </div>
                <div id="contacts-container">
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>