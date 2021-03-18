<?php
    use classes\{Token, Config};

    $user_profile_picture = $profile_user_picture = Config::get("root/path") . (empty($fetched_user->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $fetched_user->getPropertyValue("picture"));
    $private = $user->getPropertyValue("private");
?>

<div class="flex-space" id="owner-profile-menu-and-profile-edit">
    <div class="row-v-flex">
        <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
        <a href="" class="profile-menu-item">Photos</a>
        <a href="" class="profile-menu-item">Videos</a>
        <a href="" class="profile-menu-item">Likes</a>
        <a href="" class="profile-menu-item">Comments</a>
    </div>
    <div>
        <a href="" class="button-style-2" id="edit-profile-button">Edit profile</a>
        <div class="viewer">
            <div id="edit-profile-container">
                <div class="flex-space" id="edit-profile-header">
                    <a href="" class="close-style-1 close-viewer"></a>
                    <h2 class="title-style-5 black">Edit profile</h2>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="save-profile-edits-form" enctype="multipart/form-data">
                        <input type="hidden" name="save_token" value="<?php echo Token::generate("saveEdits"); ?>">
                        <input type="submit" value="Save" name="save-profile-edites" class="button-style-3">
                    </form>

                </div>
                <div id="edit-sub-container">
                    <div id="picture-and-cover-container">
                        <div href="" id="change-cover-button">
                            <div id="cover-changer-container" class="relative">
                                <img src="public/assets/images/icons/change-image.png" class="absolute image-size-1 change-image-icon" alt="">
                                <input type="file" class="absolute change-image-icon" id="change-cover" style="opacity: 0;" name="cover" form="save-profile-edits-form">
                                <img src="<?php echo $fetched_user->getPropertyValue("cover"); ?>" id="cover-changer-dim" alt="">
                                <img src="" id="cover-changer-shadow" style="z-index: 0" class="absolute" alt="">
                            </div>
                        </div>
                        <div class="relative flex-justify">
                            <div id="change-picture-button" class="absolute">
                                <div id="picture-changer-container" class="relative">
                                    <img src="<?php echo $user_profile_picture ?>" class="former-picture-dim" alt="">
                                    <img src="public/assets/images/icons/change-image.png" class="absolute change-image-icon" alt="">
                                    <input type="file" class="absolute change-image-icon" id="change-avatar" style="opacity: 0;" name="picture" form="save-profile-edits-form">
                                    <img src="" class="former-picture-dim former-picture-shadow absolute" style="z-index: 0" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="textual-data-edit">
                        <div class="field-style-1">
                            <label for="display-name" class="label-style-1">First name</label>
                            <input type="text" form="save-profile-edits-form" class="input-style-1" value="<?php echo htmlspecialchars($fetched_user->getPropertyValue("firstname")); ?>" name="firstname">
                        </div>
                        <div class="field-style-1">
                            <label for="display-name" class="label-style-1">Last name</label>
                            <input type="text" form="save-profile-edits-form" class="input-style-1" value="<?php echo htmlspecialchars($fetched_user->getPropertyValue("lastname")); ?>" name="lastname">
                        </div>
                        <div class="field-style-1">
                            <label for="bio" class="label-style-1">Bio</label>
                            <textarea type="text" form="save-profile-edits-form" maxlength="800" class="textarea-style-1" placeholder="Add your bio.." name="bio"><?php echo $fetched_user->getPropertyValue('bio'); ?></textarea>
                        </div>
                        <div class="field-style-2" style="margin-bottom: 12px">
                            <label for="private" class="label-style-1">Private account</label>
                            <div class="toggle-button-style-1" id="private-account-button"></div>
                            <input type="hidden" form="save-profile-edits-form" name="private" value="<?php echo $private ?>" id="private-account-state">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>