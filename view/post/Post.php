<?php

    namespace view\post;

    use classes\{Config};
    use models\User;

    class Post {
        function generate_timeline_post($post) {
            $post_owner_user = new User();
            $post_owner_user->fetchUser("id", $post->get_property("post_owner"));

            $post_owner_picture = Config::get("root/path") . (($post_owner_user->getPropertyValue("picture") != "") ? $post_owner_user->getPropertyValue("picture") : "assets/images/icons/user.png");
            
            $post_owner_name = $post_owner_user->getPropertyValue("firstname") . " " . $post_owner_user->getPropertyValue("lastname") . " -@" . $post_owner_user->getPropertyValue("username");

            $post_date = $post->get_property("post_date");
            $post_date = date("F d \a\\t Y h:i A",strtotime($post_date)); //January 9 at 1:34 PM

            $post_owner_profile = Config::get("root/path") . "profile.php?username=" . $post_owner_user->getPropertyValue("username");

            $post_image = $post->get_property("picture_media");
            $post_text_content = htmlspecialchars_decode($post->get_property("text_content"));

            return <<<EOS
                <div class="timeline-post">
                    <div class="post-header flex-space">
                        <div class="post-header-without-more-button">
                            <img src="$post_owner_picture" class="image-style-7 post-owner-picture" alt="">
                            <div class="post-header-textual-section">
                                <a href="$post_owner_profile" class="post-owner-name">$post_owner_name</a>
                                <div class="row-v-flex">
                                    <p class="regular-text"><a href="" class="post-date">$post_date</a> <span style="font-size: 8px">.</span></p>
                                    <img src="assets/images/icons/public-white.png" class="image-style-8" alt="" style="margin-left: 8px">
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="" class="button-style-6 dotted-more-back"></a>
                        </div>
                    </div>
                    <p class="post-text">
                        $post_text_content
                    </p>
                    <div class="media-container">
                        <div class="post-media-item-container">
                            <img src="$post_image" class="post-media-image" alt="">
                            <div class="viewer">

                            </div>
                        </div>
                    </div>
                    <div class="react-on-opost-buttons-container">
                        <a href="" class="white-like-back post-bottom-button">Like</a>
                        <a href="" class="white-comment-back post-bottom-button">Comment</a>
                        <a href="" class="white-like-back post-bottom-button">Like</a>
                    </div>
                    <div class="comment-section">
                        <div class="comment-block">
                            <div>
                                <img src="assets/images/read.png" class="image-style-9" alt="">
                            </div>
                            <div>
                                <div class="comment-wrapper">
                                    <a href="" class="comment-owner">grotto</a>
                                    <p class="comment-text">This is a comment for testing stuff</p>
                                </div>
                                <div class="row-v-flex underneath-comment-buttons-container">
                                    <a href="" class="link-style-3">like</a>
                                    <a href="" class="link-style-3">reply</a>
                                    <div style="margin-left: 6px">
                                        <p class="regular-text-style-2"> . <span class="time-of-comment">5min</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment-block">
                            <div>
                                <img src="$post_owner_picture" class="image-style-9" alt="">
                            </div>
                            <div class="comment-input-form-wrapper">
                                <form action="" method="POST" class="comment-form relative">
                                    <input type="text" name="comment" placeholder="Write a comment .." class="comment-style">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
EOS;
        }
    }

?>