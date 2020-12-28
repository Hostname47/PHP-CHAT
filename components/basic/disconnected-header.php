<?php
    use classes\{Config, Common, Token, Validation, DB, Redirect};
    use models\User;

    $pathToLogo = Config::get("root/path");

?>

<header>
    <div>
        <a href=""><img src="<?php echo $pathToLogo ?>assets/images/logos/large.png" alt="logo" class="wide-logo"></a>
    </div>
    <div class="inline-logo-separator">〡</div>
    <div id="menu-login-credentials-container">
        <div style="margin: 0 12px"></div>
        <div class="flex-column">

            <!----------------------  EMAIL OR USERNAME  ---------------------->
            <label for="email-or-phone" class="small-label">Email or Phone</label>
            <input type="text" name="email-or-username" tabindex="1" autocomplete="off" value="<?php echo htmlspecialchars(Common::getInput($_POST, 'email-or-username'));?>" class="text-input medium-text-input" form="login-form" placeholder="Email">
            
            <!----------------------  REMEMBER ME  ---------------------->
            <div class="row-v-flex">
                <input type="checkbox" tabindex="3" id="remember" form="login-form" checked>
                <label class="small-label" for="remember">Kepp me connected</label>
            </div>
        </div>
        <div style="margin: 0 4px"></div>
        <div class="flex-column">

            <!----------------------  PASSWORD  ---------------------->
            <label for="email-or-phone" class="small-label">Password</label>
            <input type="password" name="password" tabindex="2" autocomplete="off" class="text-input medium-text-input" form="login-form" placeholder="Password">
            <a href="" tabindex="5" class="link">Forgotten your passowrd?</a>

        </div>
        <div style="margin: 0 4px"></div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="flex-form" id="login-form">
            <input type="hidden" name="token_log" value="<?php echo Token::generate("login"); ?>">

            <!----------------------  LOGIN  ---------------------->
            <input type="submit" name="login" tabindex="4" value="Login" class="button-style-1">
        </form>
        <div style="margin: 0 12px 0 4px"></div>
    </div>
</header>