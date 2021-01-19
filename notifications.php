
<?php

require_once "vendor/autoload.php";
require_once "core/init.php";

use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
use models\{Post, UserRelation, Follow};
use view\post\Post as Post_View;
// DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
// Here we check if the user is not logged in and we redirect him to login page

if(!$user->getPropertyValue("isLoggedIn")) {
    Redirect::to("login/login.php");
}

$welcomeMessage = '';
if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
    $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
}

$current_user_id = $user->getPropertyValue("id");

$posts_number = Post::get_posts_number($current_user_id);
$followers_number = Follow::get_user_followers_number($current_user_id);
$followed_number = Follow::get_followed_users_number($current_user_id);
$friends_number = UserRelation::get_friends_number($current_user_id);


$journal_posts = Post::fetch_journal_posts($user->getPropertyValue("id"));
// Let's randomly sort array for now
shuffle($journal_posts);
/*usort($journal_posts, 'post_date_latest_sort');

function post_date_latest_sort($post1, $post2) {
    return $post1->get_property('post_date') == $post2->get_property('post_date') ? 0 : ($post1->get_property('post_date') > $post2->get_property('post_date')) ? -1 : 1;
}*/
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
<link rel="stylesheet" href="styles/index.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="javascript/config.js" defer></script>
<script src="javascript/header.js" defer></script>
<script src="javascript/global.js" defer></script>
</head>
<body>
<?php include_once "components/basic/header.php"; ?>
<main>
    <div id="global-container">
        <?php include_once "components/basic/master-left.php"; ?>
        <div id="master-middle">
            <div class="green-message">
                <p class="green-message-text"><?php echo $welcomeMessage; ?></p>
                <script type="text/javascript" defer>
                    if($(".green-message-text").text() !== "") {
                        $(".green-message").css("display", "block");
                    }
                </script>
            </div>
            <div>
                <h1>NOTIFICATIONS PAGE</h1>
            </div>
        </div>
    </div>
</main>
</body>
</html>