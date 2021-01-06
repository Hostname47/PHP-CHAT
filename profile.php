
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - Profile</title>
    <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/profile.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/profile.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <section id="first-section">
            <div class="relative flex-column">
                <div>
                    <div id="cover-container">
                        <img src="<?php echo $user->getPropertyValue("cover"); ?>" class="cover-photo" alt="">
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
                        <img src="<?php echo $user->getPropertyValue("picture"); ?>" class="profile-picture" alt="">
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
                <h1 class="title-style-3"><?php echo $user->getPropertyValue("firstname") . " " . $user->getPropertyValue("lastname"); ?></h1>
                <p class="regular-text-style-1">@<?php echo $user->getPropertyValue("username"); ?></p>
            </div>
            <div class="flex-space" id="owner-profile-menu-and-profile-edit">
                <div id="profile-menu">
                    <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
                    <a href="" class="profile-menu-item">Photos</a>
                    <a href="" class="profile-menu-item">Videos</a>
                    <a href="" class="profile-menu-item">Likes</a>
                    <a href="" class="profile-menu-item">Comments</a>
                </div>
                <div>
                    <a href="" class="button-style-2" id="edit-profile-button">Edit profile</a>
                    <div class="viewer">
                        <div id="edit-profile-container">
                            <div class="flex-space" id="edit-profile-header">
                                <a href="" class="close-style-1 close-viewer"></a>
                                <h2 class="title-style-5 black">Edit profile</h2>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="save-profile-edits-form" enctype="multipart/form-data">
                                    <input type="hidden" name="save_token" value="<?php echo Token::generate("saveEdits"); ?>">
                                    <input type="submit" value="Save" name="save-profile-edites" class="button-style-3">
                                </form>

                            </div>
                            <div id="edit-sub-container">
                                <div id="picture-and-cover-container">
                                    <div href="" id="change-cover-button">
                                        <div id="cover-changer-container" class="relative">
                                            <img src="assets/images/icons/change-image.png" class="absolute image-size-1 change-image-icon" alt="">
                                            <input type="file" class="absolute change-image-icon" style="opacity: 0;" name="cover" form="save-profile-edits-form">
                                            <img src="<?php echo $user->getPropertyValue("cover"); ?>" id="cover-changer-dim" alt="">
                                            <img src="" id="cover-changer-shadow" style="z-index: 0" class="absolute" alt="">
                                        </div>
                                    </div>
                                    <div class="relative flex-justify">
                                        <div id="change-picture-button" class="absolute">
                                            <div id="picture-changer-container" class="relative">
                                                <img src="<?php echo $user->getPropertyValue("picture"); ?>" class="former-picture-dim" alt="">
                                                <img src="assets/images/icons/change-image.png" class="absolute change-image-icon" alt="">
                                                <input type="file" class="absolute change-image-icon" style="opacity: 0;" name="picture" form="save-profile-edits-form">
                                                <img src="" class="former-picture-dim former-picture-shadow absolute" style="z-index: 0" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="textual-data-edit">
                                    <div class="field-style-1">
                                        <label for="display-name" class="label-style-1">First name</label>
                                        <input type="text" form="save-profile-edits-form" class="input-style-1" value="<?php echo htmlspecialchars($user->getPropertyValue("firstname")); ?>" name="firstname">
                                    </div>
                                    <div class="field-style-1">
                                        <label for="display-name" class="label-style-1">Last name</label>
                                        <input type="text" form="save-profile-edits-form" class="input-style-1" value="<?php echo htmlspecialchars($user->getPropertyValue("lastname")); ?>" name="lastname">
                                    </div>
                                    <div class="field-style-1">
                                        <label for="bio" class="label-style-1">Bio</label>
                                        <textarea type="text" form="save-profile-edits-form" maxlength="800" class="textarea-style-1" placeholder="Add your bio.." name="bio"><?php echo $user->getPropertyValue('bio'); ?></textarea>
                                    </div>
                                    <div class="field-style-2" style="margin-bottom: 12px">
                                        <label for="private" class="label-style-1">Private account</label>
                                        <div class="toggle-button-style-1" id="private-account-button"></div>
                                        <input type="hidden" form="save-profile-edits-form" name="private" value="-1" id="private-account-state">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="second-section">
            <div>
                <div class="user-info-section row-v-flex">
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">2.9K</h2>
                            <p class="regular-text-style-2">Posts</p>
                        </div>
                    </a>
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">10</h2>
                            <p class="regular-text-style-2">Followers</p>
                        </div>
                    </a>
                    <a href="" class="user-info-section-link">
                        <div>
                            <h2 class="title-style-4">156</h2>
                            <p class="regular-text-style-2">Following</p>
                        </div>
                    </a>
                </div>
                <div class="user-info-section">
                    <h2 class="title-style-4">About</h2>
                    <p class="calendar-icon regular-text-style-2">Member since <!-- Join date should be fetched from db -->March 2020</p>
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
            <div id="posts-container">
                <div class="post-item1">

                </div>    
                <div class="post-item">

                </div>
                <div class="post-item">

                </div>
            </div>
        </section>
    </main>
</body>
</html>