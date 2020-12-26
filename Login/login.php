<?php

    require_once "../core/init.php";
    require_once "../vendor/autoload.php";

    use classes\{Config, Common, Token, Validation, DB, Redirect};
    use models\User;

    if(isset($_POST["login"])) {
        if(Token::check(Common::getInput($_POST, "token"))) {
            $validate = new Validation();
    
            $validate->check($_POST, array(
                "username"=>array(
                    "required"=>true
                ),
                "password"=>array(
                    "required"=>true
                )
            ));
    
            if($validate->passed()) {
                $user = new User();

                $log = $user->login(Common::getInput($_POST, "username"), Common::getInput($_POST, "password"));

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
            <div id="registration-section">
                <h2 class="title-style1">Create an account</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex-column">

                    <div class="classic-form-input-wrapper">
                        <label for="username" class="classic-label">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars(Common::getInput($_POST, "username")); ?>" id="username" placeholder="Username" autocomplete="off" class="classic-input">
                    </div>
                    <div class="classic-form-input-wrapper">
                        <label for="password" class="classic-label">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" class="classic-input">
                    </div>
                
                    <div class="classic-form-input-wrapper">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <input type="submit" value="login" name="login" class="button-style-1" style="width: 70px;">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>