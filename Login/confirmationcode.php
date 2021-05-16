<?php

    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;

    // First we check if the user put his email and confirmatin code sent succesfully, if there's something wrong we redirect
    if(!Session::exists("email-confirmation")) {
        Redirect::to("login.php");
    }

    $validate = new Validation();

    if(isset($_POST["confirm"])) {
        if(Token::check(Common::getInput($_POST, "token_code_conf"), "reset-pasword")) {
            $validate->check($_POST, array(
                "code"=>array(
                    "name"=>"Confirmation code",
                    "required"=>true,
                    "max"=>16
                )
            ));

            if($validate->passed()) {
                if(Session::get("email-confirmation") == Common::getInput($_POST, "code")) {
                   // Here the confirmation code is good
                    Session::delete("email-confirmation");
                    Session::put("password-change-allow", "allowed");
                    Redirect::to("changepassword.php");
                } else {
                    $validate->addError("Invalide confirmation code");
                }
            }

            foreach($validate->errors() as $error) {
                echo $error . "<br>";
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
    <link rel='shortcut icon' type='image/x-icon' href='../public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/log-header.css">
    <style>
        #reset-section {
            padding: 20px;
            width: 340px;
        }
    </style>
</head>
<body>
    <?php include "../page_parts/basic/log-header.php" ?>
    <main>
        <div id="reset-section">
            <h2 class="title-style1">Email Confirmation</h2>
            <p>We sent a confirmation code into your email, copy and past it here</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="flex-column">
                <div class="classic-form-input-wrapper">
                    <label for="username" class="classic-label">Confirmation code</label>
                    <input type="text" name="code" placeholder="Enter confirmation code" autocomplete="off" class="classic-input">
                </div>
                <div class="classic-form-input-wrapper">
                    <input type="hidden" name="token_code_conf" value="<?php echo Token::generate("reset-pasword"); ?>">
                    <input type="submit" value="confirm" name="confirm" class="button-style-1" style="width: 70px;">
                </div>
            </form>
        </div>
    </main>
</body>
</html>