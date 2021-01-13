<?php

    use classes\{Config};
    use models\{User};

?>

<div class="flex-space" id="owner-profile-menu-and-profile-edit">
    <div class="row-v-flex">
        <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
        <a href="" class="profile-menu-item">Comments</a>
        <a href="" class="profile-menu-item">Photos</a>
        <a href="" class="profile-menu-item">Videos</a>
    </div>
    <div class="flex-row-column">
        <form action="<?php echo Config::get("root/path") . "functions/security/check_current_user.php" ?>" method="POST" class="flex follow-form" enctype="form-data">
            <input type="hidden" name="current_user_id" value="<?php echo $user->getPropertyValue("id"); ?>">
            <input type="hidden" name="current_profile_id" value="<?php echo $fetched_user->getPropertyValue("id"); ?>">
            <input type="submit" class="button-style-9 follow-user" value="Follow" style="margin-left: 4px; font-weight: 400">
        </form>

        <form action="<?php echo Config::get("root/path") . "functions/security/check_current_user.php" ?>" method="POST" class="flex follow-form" enctype="form-data">
            <input type="hidden" name="current_user_id" value="<?php echo $user->getPropertyValue("id"); ?>">
            <input type="hidden" name="current_profile_id" value="<?php echo $fetched_user->getPropertyValue("id"); ?>">
            <input type="submit" class="button-style-9 add-user" value="Add" style="margin-left: 8px; font-weight: 400">
        </form>
    </div>
</div>