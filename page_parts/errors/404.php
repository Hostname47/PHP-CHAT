<?php
    require "../../vendor/autoload.php";
    require "../../core/init.php";

    use classes\Config;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - ERROR</title>
    <link rel='shortcut icon' type='image/x-icon' href='../../public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/header.css">
    <link rel="stylesheet" href="../../public/css/error.css">

</head>
<body>

    <!-- 
        We need first to check whether the user is logged in, if so we need to include basic header, otherwise 
        disconnected-header will be included. (We'll do that by using condition here)
    -->
    <?php
        if($user->getPropertyValue("isLoggedIn")) {
            include_once "../basic/header.php";
        } else {
            include_once "../basic/log-header.php";
        }
    ?>
    <main>
        <div id="main-wrapper">
            <h1 class="big-error-title">404 ERROR</h1>
            <h2 class="medium-error-title">Page Not Found</h2>
            <div>
                The Page you're looking for doesn't exist or an other error occured. Go To <a href="<?php echo Config::get("root/path") . "index.php" ?>" class="classic-link-style1">Home Page</a>
            </div>
        </div>
    </main>
</body>
</html>