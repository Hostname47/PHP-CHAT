
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    // DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
    // Here we check if the user is not logged in and we redirect him to login page

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }

    if(isset($_POST["logout"])) {
        if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            Redirect::to("login/login.php");
        }
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/index.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <div id="global-container">
            <div id="master-left">
                
            </div>
            <div id="master-middle">

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
                            <p class="contact-user-name">grotto978</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>