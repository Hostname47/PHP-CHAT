<?php

use models\UserRelation;
use view\master_right\Right as MasterRightComponents;

?>

<div id="master-right">
    <div class="flex-space relative">
        <h3 class="title-style-2">Contacts</h3>
        <div>
            <a href="" id="contact-search"></a>
        </div>
        <div class="absolute" id="contact-search-field-container">
            <input type="text" id="contact-search-field" placeholder="Search by friend or group ..">
            <a class="not-link" href=""><img src="assets/images/icons/close.png" id="close-contact-search" class="image-style-4" alt=""></a>
        </div>
    </div>
    <div id="contacts-container">
        <?php
            $user_relation = new UserRelation();
            $friends = $user_relation->get_friends($current_user_id);

            $master_right = new MasterRightComponents();
            foreach($friends as $friend) {
                $master_right->generateFriendContact($current_user_id, $friend);
            }
        ?>
    </div>
</div>