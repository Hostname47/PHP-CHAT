<?php
    $logo_path = $root . "assets/images/logos/large.png";
    $index_page_path = $root . "index.php";
    $left_panel_path = $root . "settings/components/left-panel.php";

    $setting_profile_path = $root . "settings.php";
    $setting_account_path = $root . "settings/account.php";

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
                <a href="<?php echo $setting_profile_path; ?>" class="menu-button profile-button">Profile</a>
            </div>
        </div>
        <div class="button-with-suboption relative">
            <div class="relative">
                <div class="menu-button-icon profile-button-icon absolute"></div>
                <a href="<?php echo $setting_account_path; ?>" class="menu-button profile-button <?php if(isset($account_selected)) echo $account_selected; ?>">Account</a>
            </div>
        </div>
        <div class="button-with-suboption relative">
            <div class="relative">
                <div class="menu-button-icon profile-button-icon absolute"></div>
                <a href="" class="menu-button has-suboption profile-button">Profile</a>
            </div>
            <div class="button-subotions-container">
                <a href="" class="subotion">subotion0</a>
                <a href="" class="subotion">subotion1</a>
                <a href="" class="subotion">subotion2</a>
            </div>
        </div>
    </div>
</div>