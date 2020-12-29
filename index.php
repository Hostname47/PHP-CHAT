
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
        $user->logout();
        Redirect::to("login/login.php");
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
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    
    <h1>Hello <?php echo $user->getPropertyValue("username");?> !</h1>

    <form action="index.php" method="post">
        <input type="submit" name="logout" value="logout">
    </form>
</body>
</html>