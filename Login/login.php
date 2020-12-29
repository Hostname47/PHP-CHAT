<?php

    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;

    // First we check if the user is already connected we redirect him to index page
    if($user->getPropertyValue("isLoggedIn")) {
        Redirect::to("../index.php");
    }

    if(isset($_POST["login"])) {
        if(Token::check(Common::getInput($_POST, "token_log"), "login")) {
            $validate = new Validation();
            $validate->check($_POST, array(
                "email-or-username"=>array(
                    "name"=>"Email or username",
                    "required"=>true,
                    "max"=>255,
                    "min"=>6,
                    "email-or-username"=>true
                ),
                "password"=>array(
                    "name"=>"Password",
                    "required"=>true,
                    // Later
                    "strength"=>true
                )
            ));
    
            if($validate->passed()) {
                // Remember $user is created in init file
                $remember = isset($_POST["remember"]) ? true: false;
                $log = $user->login(Common::getInput($_POST, "email-or-username"), Common::getInput($_POST, "password"), $remember);
                
                if($log) {
                    Redirect::to("../index.php");
                } else {
                    echo "<p>Bad credentials</p>";
                }
            } else {
                // Here instead of printing out errors we can put them in an array and use them in proper html labels
                foreach($validate->errors() as $error) {
                    echo $error . "<br>";
                }
            }
        }
    }

    if(isset($_POST["register"])) {
        $validate = new Validation();
        if(Token::check(Common::getInput($_POST, "token_reg"), "register")) {
            $validate->check($_POST, array(
                "firstname"=>array(
                    "name"=>"Firstname",
                    "min"=>2,
                    "max"=>50
                ),
                "lastname"=>array(
                    "name"=>"Lastname",
                    "min"=>2,
                    "max"=>50
                ),
                "username"=>array(
                    "name"=>"Username",
                    "required"=>true,
                    "min"=>2,
                    "max"=>20,
                    "unique"=>true
                ),
                "email"=>array(
                    "name"=>"Email",
                    "required"=>true,
                    "email-or-username"=>true
                ),
                "password"=>array(
                    "name"=>"Password",
                    "required"=>true,
                    "min"=>6
                ),
                "password_again"=>array(
                    "name"=>"Repeated password",
                    "required"=>true,
                    "matches"=>"password"
                ),
            ));
    
            if($validate->passed()) {
                $salt = Hash::salt(16);
    
                $user = new User();
                $user->setData(array(
                    "firstname"=>Common::getInput($_POST, "firstname"),
                    "lastname"=>Common::getInput($_POST, "lastname"),
                    "username"=>Common::getInput($_POST, "username"),
                    "email"=>Common::getInput($_POST, "email"),
                    "password"=> Hash::make(Common::getInput($_POST, "password"), $salt),
                    "salt"=>$salt,
                    "joined"=> date("Y/m/d h:i:s"),
                    "user_type"=>1
                ));
                $user->add();
    
                Session::flash("register_success", "Your account has been created successfully.");
                header("Location: login.php");
                
            } else {
                foreach($validate->errors() as $key =>$error) {
                    echo "error: " . $error . "<br>";
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
    <title>V01D47 - Login</title>
    <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="stylesheet" href="../styles/log-header.css">
    <link rel="stylesheet" href="../styles/registration.css">
</head>
<body>
    <?php include "../components/basic/disconnected-header.php" ?>
    <main>
        <div class="login-img-reg-container">
            <div id="left-asset-wrapper">
                <img src="../assets/images/preview.png" id="login-image-preview" alt="">
                <h2 style="text-align: center">“The government doesn't want any system of transmitting information to remain unbroken, unless it's under its own control.” ― Isaac Asimov, Tales of the Black Widowers</h2>
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
                        <input type="hidden" name="token_reg" value="<?php echo Token::generate("register"); ?>">
                        <input type="submit" value="register" name="register" class="button-style-1" style="width: 70px;">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>