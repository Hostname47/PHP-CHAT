<?php

    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;

    if(isset($_POST["send"])) {
        if(Token::check(Common::getInput($_POST, "token_conf_send"), "reset-pasword")) {
            $validate = new Validation();
            $validate->check($_POST, array(
                "email"=>array(
                    "name"=>"Email or username",
                    "required"=>true,
                    "max"=>255,
                    "min"=>6,
                    "email-or-username"=>true
                )
            ));
    
            if($validate->passed()) {
                $exists = $user->fetchUser("email", Common::getInput($_POST, "email"));
                if($exists) {
                    // Code to send conf code
                } else {
                    // Print error message
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
    <title>Password recovery</title>
    <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/log-header.css">
    <style>
        #reset-section {
            padding: 20px;
            width: 340px;
        }
    </style>
</head>
<body>
    <?php include "../components/basic/log-header.php" ?>
    <main>
        <div id="reset-section">
            <h2 class="title-style1">Password recovery</h2>
            <p>Enter your email and click send to send a confirmation code</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex-column">
                <div class="classic-form-input-wrapper">
                    <label for="username" class="classic-label">Email</label>
                    <input type="text" name="email" value="<?php echo htmlspecialchars(Common::getInput($_POST, "email")); ?>" placeholder="Enter your email" autocomplete="off" class="classic-input">
                </div>
                <div class="classic-form-input-wrapper">
                    <input type="hidden" name="token_conf_send" value="<?php echo Token::generate("reset-pasword"); ?>">
                    <input type="submit" value="send" name="send" class="button-style-1" style="width: 70px;">
                </div>
            </form>
        </div>
    </main>
</body>
</html>