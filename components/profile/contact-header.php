<?php

    use classes\{Config};
    use models\{User, Follow, UserRelation};

?>

<div class="flex-space" id="owner-profile-menu-and-profile-edit">
    <div class="row-v-flex">
        <a href="" class="profile-menu-item profile-menu-item-selected" style="border-radius: 0">Timeline</a>
        <a href="" class="profile-menu-item">Comments</a>
        <a href="" class="profile-menu-item">Photos</a>
        <a href="" class="profile-menu-item">Videos</a>
    </div>
    <div class="flex-row-column">
        <form action="" method="GET" class="flex follow-form">
            <input type="hidden" name="current_user_id" value="<?php echo $user->getPropertyValue("id"); ?>">
            <input type="hidden" name="followed_id" value="<?php echo $fetched_user->getPropertyValue("id"); ?>">
            <?php
                $follow = new Follow();
                $follow->set_data(array(
                    "follower"=>$user->getPropertyValue("id"),
                    "followed"=>$fetched_user->getPropertyValue("id")
                ));

                if($follow->fetch_follow()) {
            ?>
            <input type="submit" class="button-style-9 follow-button followed-user" value="Followed" style="margin-left: 4px; font-weight: 400">
            <?php } else { ?>
            <input type="submit" class="button-style-9 follow-button follow-user" value="Follow" style="margin-left: 4px; font-weight: 400">
            <?php } ?>
        </form>

        <form action="" method="POST" class="flex follow-form" enctype="form-data">
            <input type="hidden" name="current_user_id" value="<?php echo $user->getPropertyValue("id"); ?>">
            <input type="hidden" name="current_profile_id" value="<?php echo $fetched_user->getPropertyValue("id"); ?>">
            <?php

                $current = $user->getPropertyValue("id");
                $friend = $fetched_user->getPropertyValue("id");

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
                        <input type="submit" class="button-style-9 added-user-back" value="Friend" style="margin-left: 8px; font-weight: 400">
EOS;
                } else if($is_pending) {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 add-user add-user-back" value="Cancel Request" style="margin-left: 8px; font-weight: 400">
EOS;
                } else if($wait_your_accept) {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 add-user add-user-back" value="Accept Request" style="margin-left: 8px; font-weight: 400">
                        <input type="submit" class="button-style-9 add-user add-user-back" value="Decline Request" style="margin-left: 8px; font-weight: 400">
EOS;
                }
                else {
                    echo <<<EOS
                        <input type="submit" class="button-style-9 add-user add-user-back" value="" style="margin-left: 8px; font-weight: 400">
EOS;
                }
                
            ?>
                
            
        </form>
    </div>
</div>