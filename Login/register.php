<?php
error_reporting();

require_once "../core/init.php";
require_once "../vendor/autoload.php";

use classes\DB;
use classes\Config;
use classes\Validation;
use classes\Common;
use classes\Session;
use classes\Token;
use models\User;

$validate = new Validation();

if(isset($_POST["register"])) {
    if(Token::check(Common::getInput($_POST, "token"))) {
        $validate->check($_POST, array(
            "firstname"=>array(
                "min"=>2,
                "max"=>50
            ),
            "lastname"=>array(
                "min"=>2,
                "max"=>50
            ),
            "username"=>array(
                "required"=>true,
                "min"=>2,
                "max"=>20,
                "unique"=>true
            ),
            "email"=>array(
                "required"=>true,
                "email"=>true
            ),
            "password"=>array(
                "required"=>true,
                "min"=>6
            ),
            "password_again"=>array(
                "required"=>true,
                "matches"=>"password"
            ),
        ));

        if($validate->passed()) {
            echo "Success";
        } else {
            foreach($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - Register</title>

    <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/registration.css">
</head>
<body>
    <?php include "../components/basic/disconnected-header.php" ?>
    <main>
        <div>
            <div id="picture-wrapper">
                <img src="" alt="">
            </div>
            <div id="registration-section">
                <h2 class="title-style1">Create an account</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex-column">
                    <div class="classic-form-input-wrapper">
                        <label for="firstname" class="classic-label">Firstname</label>
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars(Common::getInput($_POST, "firstname")); ?>" id="firstname" placeholder="Firstname" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="lastname" class="classic-label">Lastname</label>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars(Common::getInput($_POST, "lastname")); ?>" id="lastname" placeholder="Lastname" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="username" class="classic-label">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars(Common::getInput($_POST, "username")); ?>" id="username" placeholder="Username" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="email" class="classic-label">Email</label>
                        <input type="text" name="email" value="<?php echo htmlspecialchars(Common::getInput($_POST, "email")); ?>" id="email" placeholder="Email address" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="password" class="classic-label">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="password_again" class="classic-label">Re-enter you password</label>
                        <input type="password" name="password_again" id="password_again" placeholder="Re-enter password" autocomplete="off" class="classic-input">
                    </div>
                
                    <div class="classic-form-input-wrapper">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <input type="submit" value="register" name="register" class="button-style-1" style="width: 70px;">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>