
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\Post;
    // DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
    // Here we check if the user is not logged in and we redirect him to login page

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }

    $welcomeMessage = '';
    if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
        $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
    }

    

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
                    <div class="timeline-post">
                        <div class="post-header flex-space">
                            <div class="post-header-without-more-button">
                                <img src="assets/images/read.png" class="image-style-7 post-owner-picture" alt="">
                                <div class="post-header-textual-section">
                                    <a href="" class="post-owner-name">Nassri - @grotto</a>
                                    <div class="row-v-flex">
                                        <p class="regular-text"><a href="" class="post-date">January 9 at 1:34 PM</a> <span style="font-size: 8px">.</span></p>
                                        <img src="assets/images/icons/public-white.png" class="image-style-8" alt="" style="margin-left: 8px">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="" class="button-style-6 dotted-more-back"></a>
                            </div>
                        </div>
                        <p class="post-text">
                            hello community,
                            My post will sound weird or stupid to you, but I assume... After spending years and years watching movies and series of all kinds, I finally took stock, yes a point or decided to classify the series s that have scored me too much. Finally, I found the best show I could watch is Artugrul. Yes this Turkish series that little knows. Despite its budget not reaching $ 100 million in game of thrones, but the Turkish series has everything to be the best.... History, tragedy, drama, reality, hope, religion, glory..... A certain Vinking but actually....
                            I recommend everyone to see this $ 1 billion series and translated into 75 languages.
                            Thank you
                        </p>
                        <div class="media-container">
                            <div class="post-media-item-container">
                                <img src="assets/images/read.png" class="post-media-image" alt="">
                                <div class="viewer">

                                </div>
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
                                    <img src="assets/images/programming.png" class="image-style-9" alt="">
                                </div>
                                <div>
                                    <div class="comment-wrapper">
                                        <a href="" class="comment-owner">Loupgarou</a>
                                        <p class="comment-text">This is another comment for testing stuff</p>
                                    </div>
                                    <div class="row-v-flex underneath-comment-buttons-container">
                                        <a href="" class="link-style-3">like</a>
                                        <a href="" class="link-style-3">reply</a>
                                        <div style="margin-left: 6px">
                                            <p class="regular-text-style-2"> . <span class="time-of-comment">4h</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-block">
                                <div>
                                    <img src="<?php echo $user->getPropertyValue("picture"); ?>" class="image-style-9" alt="">
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