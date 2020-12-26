
<?php

    require_once "core/init.php";
    require_once "vendor/autoload.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;

    $db = DB::getInstance();
    $user = new User();

    if($user->isLoggedIn()) {
        echo "<p>Hello " . $user->getPropertyValue('username') . "</p>";
    } else {
        echo "<p>You're not logged in. If you want to do so go to <a href='login/login.php'>login</a> or <a href='login/register.php'>sign in</a>";
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
    <!-- Here before including the header, we need to make sure if user is already connected. If so
         we need to add connected header, otherwise disconnected header wil be shown
    -->
    <?php include_once "components/basic/disconnected-header.php" ?>
    <form action="index.php" method="post">
        <input type="submit" name="test" value="test">
    </form>
</body>
</html>