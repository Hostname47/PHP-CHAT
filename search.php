<?php
    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\User;
    use view\search\Search;
    // DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
    // Here we check if the user is not logged in and we redirect him to login page

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }
    if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
        $welcomeMessage = Session::flash("register_success");
    }
    if(isset($_POST["logout"])) {
        if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            Redirect::to("login/login.php");
        }
    }
    
    $search = new Search();
    $welcomeMessage = '';
    $showingNumber = 4;
    $searchKeyword = isset($_GET["q"]) ? $_GET["q"] : '';
    
    /*
    We perform search by comparing username,firstname and last name to every query string parameter and we get only users with username,firtname, or lastname
    that is like the data specified in the query string
    */
    $searchUsersResult = User::search($searchKeyword);
    $number_of_users = count($searchUsersResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - search</title>
    <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/search.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/index.js" defer></script>
    <script src="javascript/search.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <div id="global-container">
            <div id="master-left">
                
            </div>
            <div id="master-middle">
                <div class="green-message">
                    <p class="green-message-text"><?php echo $welcomeMessage; ?></p>
                    <script type="text/javascript" defer>
                        if($(".green-message-text").text() !== "") {
                            $(".green-message").css("display", "block");
                        }
                    </script>
                </div>
                <div class="search-result-type-container">
                    <div style="padding: 8px">
                        <div class="flex-space">
                            <h1 class="title-style-4">People</h1>
                            <a href="" class="link-style-2 see-more-results">see more</a>
                        </div>
                        <p class="label-style-2">Showing <span>
                            <?php echo ($number_of_users > $showingNumber) ? $showingNumber : $number_of_users; ?>
                            </span> of <span><?php echo $number_of_users; ?></span> results</p>
                    </div>
                    <div class="search-result">
                        <?php
                            $count = 0;
                            foreach($searchUsersResult as $u) {
                                if($count == $showingNumber) {
                                    break;
                                }
                                $count++;
                                echo $search->generateSearchPerson($u);
                            }
                        ?>
                        <script defer>
                            console.log($(this));
                        </script>
                    </div>
                </div>
                <div class="search-result-type-container">
                    <div style="padding: 8px">
                        <div class="flex-space">
                            <h1 class="title-style-4">Groups</h1>
                            <a href="" class="link-style-2">see more</a>
                        </div>
                        <p class="label-style-2">Showing <span>4</span> of <span>7</span> results</p>
                    </div>
                    <div class="search-result">
                        <?php
                            foreach($searchUsersResult as $u) {
                                
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div id="master-right">
                
            </div>
        </div>
    </main>
</body>
</html>