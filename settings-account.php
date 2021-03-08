<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Validation, Hash, Common, Session, Token, Redirect};
use models\User;

// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page
if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

$account_selected = 'selected-button';

$save_success_message = '';
$save_failure_message = '';

$fullname = $user->getPropertyValue("firstname") . " " . $user->getPropertyValue("lastname");
$username = $user->getPropertyValue("username");
$bio = $user->getPropertyValue("bio");
$picture = $root . (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));
$cover = $root . $user->getPropertyValue("cover");
$profile = $root . "profile.php?username=" . $user->getPropertyValue("username");
$private = $user->getPropertyValue("private");

$email = $user->getPropertyValue("email");

include_once 'functions/sanitize_text.php';

if(isset($_POST["save-changes"])) {
    if(Token::check(Common::getInput($_POST, "token_save_changes"), "saveEdits")) {

        $validator = new Validation();

        $validator->check($_POST, array(
            "email"=>array(
                "name"=>"Email",
                "required"=>true,
                "email"=>true
            ),
            "password"=>array(
                "name"=>"Password",
                "required"=>true,
                "min"=>6
            ),
            "new-password"=>array(
                "name"=>"New password",
                "required"=>true,
                "min"=>6
            ),
            "new-password-again"=>array(
                "name"=>"Repeated password",
                "required"=>true,
                "matches"=>"new-password"
            ),
        ));
    }
    if($validator->passed()) {
        if($email == sanitize_text($_POST["email"])) {
            $current_password = sanitize_text($_POST["password"]);

            $u = new User();
            if($u->fetchUser("email", $email)) {
                // Get user salt
                $salt = $u->getPropertyValue("salt");
                $pass = Hash::make($current_password, $salt);
    
                $stored_pass = $u->getPropertyValue("password");
    
                // If the password matches
                if($stored_pass == $pass) {
                    // If so, we get the new password
                    $new_password = sanitize_text($_POST["new-password"]);
                    // hash it with the old salt, it's not a big deal (You can generate new salt and update both pass and salt in db)
                    $new_password = Hash::make($new_password, $salt);

                    if($u->update_property("password", $new_password)) {
                        $save_success_message = 'Changes saved successfully !';
                    } else {
                        $save_failure_message = "Pasword updating error ! please try again in a few moments";    
                    }
                } else {
                    $save_failure_message = "Invalide password !";
                }
            } else {
                $save_failure_message = "Invalide email or not present in our records !";
            }
        } else {
            $save_failure_message = "Invalide email or not present in our records !";
        }
    } else {
        $save_failure_message = $validator->errors()[0];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>V01D47</title>
<link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico'/>
<link rel="stylesheet" href="public/css/global.css">
<link rel="stylesheet" href="public/css/settings.css">

<style>
    .setting-input-text-style, .green-message, .red-message {
        width: 80%;
    }

    #global-container {
        margin-right: 60px
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="public/javascript/config.js" defer></script>
<script src="public/javascript/settings.js" defer></script>
</head>
<body>
<main>
    <?php require_once "page_parts/settings/left-panel.php" ?>
    <div id="global-container">
        <div id="setting-master-container">
            <h1 class="no-margin">Security</h1>
            <div class="setting-block-line-separator"></div>
            <div>
                <div class="green-message">
                    <p class="green-message-text"><?php echo $save_success_message; ?></p>
                    <script>
                        if($(".green-message-text").text() !== "") {
                            $(".green-message").css("display", "block");
                        }
                    </script>
                </div>
                <div class="red-message">
                    <p class="red-message-text"><?php echo $save_failure_message; ?></p>
                    <script>
                        if($(".red-message-text").text() !== "") {
                            $(".red-message").css("display", "block");
                        }
                    </script>
                </div>
                <div class="flex-column">
                    <label for="email" class="setting-label1">E-mail address<span class="red-label">*</span></label>
                    <input type="text" form="save-form" class="setting-input-text-style" autocomplete="off" value="<?php echo $email; ?>" name="email" id="email">
                </div>
                <div class="flex-column">
                    <label for="password" class="setting-label1">Current password<span class="red-label">*</span></label>
                    <input type="password" form="save-form" class="setting-input-text-style" name="password" id="password">
                </div>
                <div class="flex-column">
                    <label for="new-password" class="setting-label1">New password</label>
                    <input type="password" form="save-form" class="setting-input-text-style" name="new-password" id="new-password">
                </div>
                <div class="flex-column">
                    <label for="new-password-again" class="setting-label1">Confirm new password</label>
                    <input type="password" form="save-form" class="setting-input-text-style" name="new-password-again" id="new-password-again">
                </div>
                
                <form action="" method="POST" id="save-form" enctype="multipart/form-data">
                    <input type="hidden" name="token_save_changes" value="<?php echo Token::generate("saveEdits"); ?>">
                    <input type="submit" value="SAVE CHANGES" name="save-changes" id="save-button">
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>