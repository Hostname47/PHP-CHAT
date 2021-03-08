<?php

    use classes\{Config, Session, Token, Common, Redirect};

    if(isset($_POST["logout"])) {
        if(Token::check(Common::getInput($_POST, "token_logout"), "logout")) {
            $user->logout();
            Redirect::to("login/login.php");
        }
    }

    $logo_path = $root . "public/assets/images/logos/large.png";
    $index_page_path = $root . "index.php";

    $setting_profile_path = $root . "settings.php";
    $setting_account_path = $root . "settings-account.php";

?>
<div id="setting-left-pannel">
    <div id="setting-panel-header-logo-box">
        <a href="<?php echo $index_page_path ?>" class="flex"><img src="<?php echo $logo_path ?>" id="setting-panel-header-logo" alt=""></a>
    </div>
    <div class="flex">
        <a href="<?php echo $index_page_path ?>" class="flex link go-back-to right-pos-margin" style="margin-right: 6px">Go back to VO1D47</a>
    </div>
    <div id="left-panel-menu">
        <div class="button-with-suboption relative <?php if(isset($profile_selected)) echo $profile_selected; ?>">
            <div class="relative">
                <div class="menu-button-icon profile-button-icon absolute"></div>
                <a href="<?php echo $setting_profile_path; ?>" class="menu-button">Profile</a>
            </div>
        </div>
        <div class="button-with-suboption relative">
            <div class="relative">
                <div class="menu-button-icon account-button-icon absolute"></div>
                <a href="<?php echo $setting_account_path; ?>" class="menu-button <?php if(isset($account_selected)) echo $account_selected; ?>">Account</a>
            </div>
        </div>
        <div class="button-with-suboption relative">
            <div class="relative">
                <div class="menu-button-icon not-yet absolute"></div>
                <a href="" class="menu-button has-suboption">NOT YET</a>
            </div>
            <div class="button-subotions-container">
                <a href="" class="subotion">subotion0</a>
                <a href="" class="subotion">subotion1</a>
                <a href="" class="subotion">subotion2</a>
            </div>
        </div>
        <div class="button-with-suboption relative">
            <button name="logout" type="submit" form="logout-form" class="logout-btn">
            
            </button>
            <div class="relative">
                <div class="menu-button-icon logout-button-icon absolute"></div>
                <a href="<?php echo $setting_profile_path; ?>" class="menu-button profile-button">Logout</a>
            </div>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="logout-form">
            <input type="hidden" name="token_logout" value="<?php 
                if(Session::exists("logout")) 
                    echo Session::get("logout");
                else {
                    echo Token::generate("logout");
                }
            ?>">
        </form>
    </div>
</div>