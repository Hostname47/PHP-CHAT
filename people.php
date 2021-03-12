<?php
    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\User;
    use layouts\search\Search;
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
    $welcomeMessage = '';
    
    $search = new Search();
    $showingNumber = 4;
    
    $searchKeyword = isset($_GET["q"]) ? $_GET["q"] : '';
    
    /*
    We perform search by comparing username,firstname and last name to every query string parameter and we get only users with username,firtname, or lastname
    that is like the data specified in the query string
    */
    $searchUsersResult = User::search($searchKeyword);
    $number_of_users = count($searchUsersResult);
    $dataExists = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47 - search</title>
    <link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/index.css">
    <link rel="stylesheet" href="public/css/search.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="public/javascript/config.js" defer></script>
    <script src="public/javascript/index.js" defer></script>
    <script src="public/javascript/global.js" defer></script>
    <script src="public/javascript/search.js" defer></script>
</head>
<body>
    <?php include_once "page_parts/basic/header.php"; ?>
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
                <div class="no-search-container flex-row-column">
                    <p class="no-search-results">No search results.</p>
                </div>
                <div class="search-result-type-container">
                    <div style="padding: 8px">
                        <div>
                            <h1 class="title-style-4">People</h1>
                        </div>
                        <p class="label-style-2">Showing <span>
                            <?php echo ($number_of_users > $showingNumber) ? $showingNumber : $number_of_users; ?>
                            </span> of <span><?php echo $number_of_users; ?></span> results</p>
                    </div>
                    <div class="search-result">
                        <?php
                            foreach($searchUsersResult as $u) {
                                echo $search->generateSearchPerson($user->getPropertyValue("id"), $u);
                            }
                        ?>
                    </div>
                </div>
                <script defer>
                    $(document).ready(function () {
                        let containers = $(".search-result-type-container");
                        let dataExists = false;
                        jQuery.each(containers, function(index, item) {
                            /*
                            Here when we loop through all containers, we need to check wether at least one of the containers
                            contains a data, if so we don't have to print results not found.
                            */
                            if($(this).find(".search-result .search-result-item").length != 0) {
                                dataExists = true;
                                $(this).css("display", "block");
                            }
                        })

                        if(!dataExists) {
                            $(".no-search-container").css("display", "flex");
                            console.log("display error !");
                        } else {
                            $(".no-search-container").css("display", "none");
                            console.log("none");
                        }
                    });
                </script>
            </div>
        </div>
    </main>
</body>
</html>