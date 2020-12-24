<?php
error_reporting();

require_once "../core/init.php";
require_once "../vendor/autoload.php";

use classes\Config;
use classes\DB;
use models\User;

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
                <form action="" method="post" class="flex-column">
                    <div class="classic-form-input-wrapper">
                        <label for="firstname" class="classic-label">Firstname</label>
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="lastname" class="classic-label">Lastname</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="username" class="classic-label">Username</label>
                        <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="email" class="classic-label">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email address" autocomplete="off" class="classic-input">
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
                        <input type="submit" value="register" class="button-style-1" style="width: 70px;">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>