
<?php
    
    require_once "core/init.php";
    require_once "vendor/autoload.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash};
    use models\User;

    if(Session::exists('register_success')) {
        echo Session::flash('register_success');
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
    

</body>
</html>