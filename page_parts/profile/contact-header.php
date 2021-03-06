<?php

    use classes\{Config};
    use models\{User, Follow, UserRelation};
    
    $current = $user->getPropertyValue("id");
    $friend = $fetched_user->getPropertyValue("id");

    $follow = new Follow();
    $follow->set_data(array(
        "follower"=>$current,
        "followed"=>$friend
    ));


?>

<div class="flex-space" id="owner-profile-menu-and-profile-edit">
    <div class="row-v-flex">
        <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
        <a href="" class="profile-menu-item">Comments</a>
        <a href="" class="profile-menu-item">Photos</a>
        <a href="" class="profile-menu-item">Videos</a>
    </div>
    <div class="flex-row-column">
        <form action="" method="GET" class="flex follow-form follow-menu-header-form">
            <input type="hidden" name="current_user_id" value="<?php echo $current ?>">
            <input type="hidden" name="current_profile_id" value="<?php echo $friend ?>">
            <?php
                if($follow->fetch_follow()) {
                    $follow_unfollow = <<<EOS
                        <div class="sub-option-style-2 post-to-option">
                            <label for="" class="flex padding-back-style-1 unfollow-black follow-label">Unfollow</label>
                            <input type="submit" class="button-style-9-black follow-button" value="Unfollow" style="margin-left: 8px; font-weight: 400">
                        </div>
EOS;
            ?>
            <input type="submit" class="button-style-9 follow-button followed-user" value="Followed" style="margin-left: 4px; font-weight: 400">
            <?php } else {
                $follow_unfollow = <<<EOS
                    <div class="sub-option-style-2 post-to-option">
                        <label for="" class="flex padding-back-style-1 follow-black follow-label">Follow</label>
                        <input type="submit" class="button-style-9-black follow-button" value="Follow" style="margin-left: 8px; font-weight: 400">
                    </div>
EOS;            
            ?>
            <input type="submit" class="button-style-9 follow-button follow-user" value="Follow" style="margin-left: 4px; font-weight: 400">
            <?php } ?>
        </form>

        <form action="" method="POST" class="flex follow-form" enctype="form-data">
            <input type="hidden" name="current_user_id" value="<?php echo $user->getPropertyValue("id"); ?>">
            <input type="hidden" name="current_profile_id" value="<?php echo $fetched_user->getPropertyValue("id"); ?>">
            <?php

                $user_relation = new UserRelation();
                $user_relation->set_property("from", $current);
                $user_relation->set_property("to", $friend);

                $is_blocked = $user_relation->get_relation_by_status("B");
                $is_friend = $user_relation->get_relation_by_status("F");
                $is_pending = $user_relation->get_relation_by_status("P");
                /*
                                                                          -------  --------
                                                                            from      to                                                                     
                */
                $wait_your_accept = $user_relation->micro_relation_exists($friend, $current, "P");

                if($is_blocked) {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 block-user unblock-user-back" value="Unblock" style="margin-left: 8px; font-weight: 400">
EOS;
                } 
                if($is_friend){
                    echo <<<EOS
                        <div class="relative">
                            <input type="submit" class="button-style-9 added-user-back button-with-suboption friend-state-button" value="Friend" style="margin-left: 8px; font-weight: 400">
                            <div class="sub-options-container sub-options-container-style-2" style="top: 40px; z-index: 3">
                                <div class="paragraph-wrapper-style-1">
                                    <p class="label-style-2">Friend options</p>
                                </div>
                                <div class="options-container-style-1">
                                    <div class="sub-option-style-2 post-to-option">
                                        <label for="" class="flex padding-back-style-1 unfriend-black">Unfriend</label>
                                        <input type="submit" class="button-style-9-black unfriend" value="Unfriend" style="margin-left: 8px; font-weight: 400">
                                    </div>
                                </div>
                                <div class="options-container-style-1">
                                    $follow_unfollow
                                </div>
                            </div>
                        </div>

EOS;
                } else if($is_pending) {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 add-user unfriend-white-back" value="Cancel Request" style="margin-left: 8px; font-weight: 400">
EOS;
                } else if($wait_your_accept) {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 accept-user add-user-back" value="Accept Request" style="margin-left: 8px; font-weight: 400">
                        <input type="submit" class="button-style-9 decline-user unfriend-white-back" value="Decline Request" style="margin-left: 8px; font-weight: 400">
EOS;
                }
                else {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 add-user add-user-back" value="Add" style="margin-left: 8px; font-weight: 400">
EOS;
                }
                
            ?>
                
            
        </form>
    </div>
</div>