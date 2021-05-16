<?php

    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;
    use Mailgun\Mailgun;

    $validate = new Validation();

    if(isset($_POST["send"])) {
        if(Token::check(Common::getInput($_POST, "token_conf_send"), "reset-pasword")) {
            $validate->check($_POST, array(
                "email"=>array(
                    "name"=>"Email",
                    "required"=>true,
                    "max"=>255,
                    "min"=>6,
                    "email"=>true
                )
            ));

            if($validate->passed()) {
                $exists = $user->fetchUser("email", Common::getInput($_POST, "email"));
                if($exists) {
                    $conf_code = substr(Hash::unique(), 16, 16);
                    // Code to send conf code
                    $mg = new Mailgun("YOUR_KEY");
                    $domain = "YOUR_DOMAIN";
                    # Make the call to the client.
                    try {
                        $result = $mg->sendMessage($domain, array(
                            'from'	=> '<SENDER_EMAIL>',
                            'to'	=> '<' . $user->getPropertyValue("email") . '>',
                            'subject' => "Confirmation code",
                            'text'	=> $conf_code
                        ));

                        /* We'll use email-confirmation sesion variable in the next page to see if the user pass from this process,
                        If the user try directly to go to the next page, he'll not be able to access it because email-confirmation
                        session variable will not be set here and we redirect him to login page*/
                        Session::put("email-confirmation", $conf_code);

                        // We'll need this variable to fetch the user data in th next pages of password recovery
                        Session::put("u_id", $user->getPropertyValue("id"));
                        $user->fetchUser("email", Common::getInput($_POST, "email"));
                        Redirect::to("confirmationcode.php");
                    } catch(Exception $e) {
                        $validate->addError("There's a problem while sending the confirmation code.");
                        $validate->addError("If send message throws an error about ssl certificat, read [IMPORTANT#5] in Z_IMPORTANT.txt file in root directory to resolve this problem!");
                    }
                    
                } else {
                    // Print error message
                    echo "There's no user with this email address !";
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
            <h2 class="title-style1">Password recovery</h2>
            <p>Enter your email and click send button to get a confirmation code on your email box.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex-column">
                <div class="classic-form-input-wrapper">
                    <label for="username" class="classic-label">Email</label>
                    <input type="text" name="email" placeholder="Enter your email" autocomplete="off" class="classic-input">
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