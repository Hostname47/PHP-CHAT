<?php

    namespace layouts\post;

    use classes\{Config};
    use models\{User, Comment, Like, Shared_Post, Post as Pst};

    class Post {

        function generate_post($post, $user) {
            $root = Config::get("root/path");
            $project_name = Config::get("root/project_name");
            $project_path = $_SERVER['DOCUMENT_ROOT'] . "/" . $project_name . "/";

            $current_user_id = $user->getPropertyValue("id");
            $current_user_picture = Config::get("root/path") . (($user->getPropertyValue("picture") != "") ? $user->getPropertyValue("picture") : "public/assets/images/icons/user.png");

            $post_owner_user = new User();
            $post_owner_user->fetchUser("id", $post->get_property("post_owner"));

            $post_owner_picture = Config::get("root/path") . (($post_owner_user->getPropertyValue("picture") != "") ? $post_owner_user->getPropertyValue("picture") : "public/assets/images/logos/logo512.png");
            
            $post_id= $post->get_property("post_id");
            $post_owner_name = $post_owner_user->getPropertyValue("firstname") . " " . $post_owner_user->getPropertyValue("lastname") . " -@" . $post_owner_user->getPropertyValue("username");

            $post_owner_actions = "";

            if($post->get_property("post_owner") == $user->getPropertyValue('id')) {
                $post_owner_actions = <<<E
                    <div class="sub-option-style-2">
                        <a href="" class="black-link delete-post">Delete post</a>
                    </div>
                    <div class="sub-option-style-2">
                        <a href="" class="black-link edit-post">Edit post</a>
                    </div>
E;
            }

            $post_date = $post->get_property("post_date");
            $post_date = date("F d \a\\t Y h:i A",strtotime($post_date)); //January 9 at 1:34 PM

            $post_visibility = "";
            if($post->get_property('post_visibility') == 1) {
                $post_visibility = "public/assets/images/icons/public-white.png";
            } else if($post->get_property('post_visibility') == 2) {
                $post_visibility = "public/assets/images/icons/group-w.png";
            }  else if($post->get_property('post_visibility') == 3) {
                $post_visibility = "public/assets/images/icons/lock-white.png";
            } 

            $post_owner_profile = Config::get("root/path") . "profile.php?username=" . $post_owner_user->getPropertyValue("username");

            $image_components = "";
            $video_components = "";

            $post_images_location = $post->get_property("picture_media");
            $post_videos_location = $post->get_property("video_media");

            $post_images_dir = $project_path . $post->get_property("picture_media");
            $post_videos_dir = $project_path . $post->get_property("video_media");

            $post_text_content = htmlspecialchars_decode($post->get_property("text_content"));
            if($post_images_location != null && is_dir($post_images_dir)) {
                if($this->is_dir_empty($post_images_dir) == false) {
                    $fileSystemIterator = new \FilesystemIterator($post_images_dir);
                    foreach ($fileSystemIterator as $fileInfo){
                        $image_components .= $this->generate_post_image($root . $post_images_location . $fileInfo->getFilename());
                    }
                }
            }

            if($post_videos_location != null && is_dir($post_videos_dir)) {
                if($this->is_dir_empty($post_videos_dir) == false) {

                    $fileSystemIterator = new \FilesystemIterator($post_videos_dir);
                    foreach ($fileSystemIterator as $fileInfo){
                        $src = $root . $post_videos_location . $fileInfo->getFilename();
                        $video_components = <<<VIDEO
                        <video class="post-video" controls>
                            <source src="$src" type="video/mp4">
                            <source src="movie.ogg" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
VIDEO;
                    }
                }
            }

            $like_class = "white-like-back";
            $nodisplay = 'no-display';
            $shared_post_component = "";
            if($post->get_property("is_shared")) {
                // We don't want the entire post so we're forced to hard code it hhh
                //$shared_post_component = $this->generate_post($shared_post, $user);

                $shared = new Pst();
                $shared->fetchPost($post->get_property("post_shared_id"));

                $shared_post_owner_user = new User();
                $shared_post_owner_user->fetchUser("id", $shared->get_property("post_owner"));

                $shared_post_owner_picture = Config::get("root/path") . (($shared_post_owner_user->getPropertyValue("picture") != "") ? $shared_post_owner_user->getPropertyValue("picture") : "public/assets/images/logos/logo512.png");
                
                $shared_post_id= $shared->get_property("post_id");
                $shared_post_owner_name = $shared_post_owner_user->getPropertyValue("firstname") . " " . $shared_post_owner_user->getPropertyValue("lastname") . " -@" . $shared_post_owner_user->getPropertyValue("username");

                $shared_post_date = $shared->get_property("post_date");
                $shared_post_date = date("F d \a\\t Y h:i A",strtotime($shared_post_date)); //January 9 at 1:34 PM

                $shared_post_owner_profile = Config::get("root/path") . "profile.php?username=" . $shared_post_owner_user->getPropertyValue("username");

                $shared_post_visibility = "";
                if($post->get_property('post_visibility') == 1) {
                    $shared_post_visibility = "public/assets/images/icons/public-white.png";
                } else if($post->get_property('post_visibility') == 2) {
                    $shared_post_visibility = "public/assets/images/icons/group-w.png";
                }  else if($post->get_property('post_visibility') == 3) {
                    $shared_post_visibility = "public/assets/images/icons/lock-white.png";
                }

                $shared_image_components = "";
                $shared_video_components = "";

                $shared_post_images_location = $shared->get_property("picture_media");
                $shared_post_videos_location = $shared->get_property("video_media");

                $shared_post_images_dir = $project_path . $shared->get_property("picture_media");
                $shared_post_videos_dir = $project_path . $shared->get_property("video_media");

                if($post->get_property('post_shared_id') == null) {
                    $shared_post_text_content = <<<e
                        <div class="clickable">
                            <a href="#" class="post-text link-style-3">POST DELETED</a>
                            <p class="regular-text post-text"><em>The owner of this post is either delete that post or make it private ..</em></p>
                        </div>
                    e;
                } else {
                    $shared_post_text_content = htmlspecialchars_decode($shared->get_property("text_content"));
                }

                if(is_dir($shared_post_images_dir) && $shared_post_images_dir != $project_path) {
                    if($this->is_dir_empty($shared_post_images_dir) == false) {
                        $fileSystemIterator = new \FilesystemIterator($shared_post_images_dir);
                        foreach ($fileSystemIterator as $fileInfo){
                            $shared_image_components .= $this->generate_post_image($root . $shared_post_images_location . $fileInfo->getFilename());
                        }
                    }
                }

                if(is_dir($shared_post_videos_dir) && $shared_post_videos_dir != $project_path) {
                    if($this->is_dir_empty($shared_post_videos_dir) == false) {

                        $fileSystemIterator = new \FilesystemIterator($shared_post_videos_dir);
                        foreach ($fileSystemIterator as $fileInfo){
                            $src = $root . $shared_post_videos_location . $fileInfo->getFilename();
                            $shared_video_components = <<<VIDEO
                            <video class="post-video" controls>
                                <source src="$src" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
    VIDEO;
                        }
                    }
                }

                $shared_post_component = <<<SHARED_POST
                <div class="post-item">
                    <div class="timeline-post image-post">
                        <div class="post-header flex-space">
                            <div class="post-header-without-more-button">
                                <div class="post-owner-picture-container">
                                    <img src="$shared_post_owner_picture" class="post-owner-picture" alt="">
                                </div>
                                <div class="post-header-textual-section">
                                    <a href="$shared_post_owner_profile" class="post-owner-name">$shared_post_owner_name</a>
                                    <div class="row-v-flex">
                                        <p class="regular-text"><a href="" class="post-date">$shared_post_date</a> <span style="font-size: 8px">.</span></p>
                                        <img src="$shared_post_visibility" class="image-style-8" alt="" style="margin-left: 8px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="post-text">
                            $shared_post_text_content
                        </p>
                        <div class="media-container">
                            $shared_video_components
                            $shared_image_components
                        </div>
                    </div>
                    <input type="hidden" class="pid" value="$shared_post_id">
                </div>
SHARED_POST;
            }

            $post_meta_like = <<<LM
            <div class="no-display post-meta-likes post-meta"><span class="meta-count">0</span>Likes</div>
LM;;
            $post_meta_comment = <<<CM
            <div class="no-display post-meta-comments post-meta"><span class="meta-count">0</span>Comments</div>
CM;
            $post_meta_share = <<<SM
            <div class="no-display post-meta-shares post-meta"><span class="meta-count">0</span>Shares</div>
SM;


            // Comment meta
            $pmc = count(Comment::fetch_post_comments($post_id));
            if($pmc > 0) {
                $post_meta_comment = <<<CM
                <div class="post-meta-comments post-meta"><span class="meta-count">$pmc</span>Comments</div>
CM;
            }

            $like_manager = new Like();
            if(($likes_count = count($like_manager->get_post_users_likes_by_post($post_id))) > 0) {
                $post_meta_like = <<<LM
                <div class="post-meta-likes post-meta"><span class="meta-count">$likes_count</span>Likes</div>
LM;
            }

            if(($shares = Pst::get_post_share_numbers($post_id)) > 0) {
                $nodisplay = '';
                $post_meta_share = <<<SM
                <div class="post-meta-shares post-meta"><span class="meta-count">$shares</span>Shares</div>
SM;
            }

            $like_manager->setData(array(
                "user_id"=>$current_user_id,
                "post_id"=>$post_id
            ));
            $like_class = "white-like-back";
            if($like_manager->exists()) {
                $like_class = "white-like-filled-back bold";
            }

            $comments_components = '';
            foreach(Comment::fetch_post_comments($post_id) as $comment) {
                $cm = new Comment();
                $cm->fetch_comment($comment->id);

                $comments_components .= self::generate_comment($cm, $current_user_id);
            }

            return <<<EOS
            <div class="post-item">
                <div class="timeline-post image-post">
                    <div class="post-header flex-space">
                        <div class="post-header-without-more-button">
                            <div class="post-owner-picture-container">
                                <img src="$post_owner_picture" class="post-owner-picture" alt="">
                            </div>
                            <div class="post-header-textual-section">
                                <a href="$post_owner_profile" class="post-owner-name">$post_owner_name</a>
                                <div class="row-v-flex">
                                    <p class="regular-text"><a href="" class="post-date">$post_date</a> <span style="font-size: 8px">.</span></p>
                                    <img src="$post_visibility" class="image-style-8" alt="" style="margin-left: 8px">
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <a href="" class="button-style-10 dotted-more-back button-with-suboption"></a>
                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1; width: 129px; top: 30px; left: -100px; padding: 4px">
                                $post_owner_actions
                                <div class="sub-option-style-2">
                                    <a href="" class="black-link hide-post">Hide post</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="post-text">
                        $post_text_content
                    </p>
                    <div class="post-edit-container relative" style="padding: 0 10px; box-sizing: border-box">
                        <textarea autocomplete="off" class="editable-input post-editable-text"></textarea>
                        <div class="close-post-edit"></div>
                    </div>
                    <div class="shared_post_container">
                        $shared_post_component
                    </div>
                    <div class="media-container">
                        $video_components
                        $image_components
                    </div>
                    <div class="post-statis row-v-flex $nodisplay">
                        $post_meta_like
                        <div class="right-pos-margin row-v-flex">
                            $post_meta_comment
                            $post_meta_share
                        </div>
                    </div>
                    <div class="react-on-opost-buttons-container">
                        <a href="" class="$like_class post-bottom-button like-button">Like</a>
                        <a href="" class="white-comment-back post-bottom-button write-comment-button">Comment</a>
                        <div class="relative share-button-container">
                            <a href="" class="reply-back post-bottom-button share-button">Share</a>
                            <div class="share-animation-container flex-row-column">
                                <div class="share-animation-outer-circle-container">
                                    
                                </div>
                                <div class="share-animation-inner-circle-container">
                                    
                                </div>
                                <div class="animation-hand">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="comment-section">
                        $comments_components
                        <div class="owner-comment">
                            <div class="comment-block">
                                <div class="comment-op">
                                    <div class="comment_owner_picture_container">
                                        <img src="$current_user_picture" class="comment_owner_picture" alt="">
                                    </div>
                                </div>
                                <div class="comment-input-form-wrapper">
                                    <form action="" method="POST" class="comment-form relative">
                                        <input type="text" name="comment" autocomplete="off" placeholder="Write a comment .." class="comment-style comment-input">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="pid" value="$post_id">
            </div>

EOS;
        }

        public static function generate_comment($comment, $current_user_id) {

            $comment_owner = new User();
            $comment_owner->fetchUser('id', $comment->get_property("comment_owner"));

            $comment_owner_picture = Config::get("root/path") . 
                (empty($comment_owner->getPropertyValue("picture")) ? "public/assets/images/logos/logo512.png" : $comment_owner->getPropertyValue("picture"));
            $comment_owner_username = $comment_owner->getPropertyValue("username");
            $comment_owner_profile = Config::get("root/path") . "profile.php?username=" . $comment_owner_username;
            $comment_text = $comment->get_property("comment_text");
            $comment_id = $comment->get_property("id");

            $now = strtotime("now");
            $seconds = floor($now - strtotime($comment->get_property("comment_date")));
            
            if($seconds > 29030400) {
                $comment_life = floor($seconds / 29030400) . "y";
            } else if($seconds > 604799 && $seconds < 29030400) {
                $comment_life = floor($seconds / 604800) . "w";
            } else if($seconds < 604799 && $seconds > 86400) {
                $comment_life = floor($seconds / 86400) . "d";
            } else if($seconds < 86400 && $seconds > 3600) {
                $comment_life = floor($seconds / 3600) . "h";
            } else if($seconds < 3600 && $seconds > 60) {
                $comment_life = floor($seconds / 60) . "min";
            } else if($seconds > 15){
                $comment_life = $seconds . "sec";
            } else {
                $comment_life = "Now";
            }

            /*
                Here we want to give the user the ability to delete a comment only in two situations:
                1- If the comment owner if the same user logged in
                2- if the user who is currently logging in is the owner of the post (Here we need to get post owner from Post table
                by using comment post_id)
                -----------
                First we get the post id from comment and then pass it to get_post_owner function to get the owner of post
                Notice only the comment owner could edit his comment. The post owner could only delete it
            */
            
            $comment_options = <<<CO
    <div class="relative comment">
        <div class="comment-options-button"></div>
        <div class="sub-options-container sub-options-container-style-2" style="z-index: 1; width: 129px; top: 20px; left: -100px">
            <div class="options-container-style-1 black">
CO;

            $owner_of_post_contains_current_comment = $comment->get_property('post_id');
            // We use Pst as Post model alias cauz we alsready have Post view manager in use
            $owner_of_post_contains_current_comment = Pst::get_post_owner($owner_of_post_contains_current_comment);
            if(($comment->get_property("comment_owner") == $current_user_id)
                || $current_user_id == $owner_of_post_contains_current_comment->post_owner)
            {
                if($comment->get_property("comment_owner") == $current_user_id) {
                    $comment_options .= <<<CO
                <div class="sub-option-style-2">
                    <a href="" class="black-link edit-comment">Edit comment</a>
                </div>
CO;
                }
                $comment_options .= <<<CO
                <div class="sub-option-style-2">
                    <a href="" class="black-link delete-comment">Delete comment</a>
                </div>
CO;
            }

            $comment_options .= <<<CO
            <div class="sub-option-style-2">
                <a href="" class="black-link hide-button">Hide comment</a>
            </div>
        </div>
    </div>
</div>
CO;

            return <<<COM
                <div class="comment-block">
                    <input type="hidden" class="comment_id" value="$comment_id">
                    <div class="small-text hidden-comment-hint">The comment is hidden ! click <span class="show-comment">here</span> to show it again</div>
                    <div class="comment-op">
                        <div class="comment_owner_picture_container">
                            <img src="$comment_owner_picture" class="comment_owner_picture" alt="TT">
                        </div>
                    </div>
                    <div class="comment-global-wrapper">
                        <div class="row-v-flex">
                            <div class="comment-wrapper">
                                <div>
                                    <a href="$comment_owner_profile" class="comment-owner">$comment_owner_username</a>
                                    <p class="comment-text">$comment_text</p>
                                    <div class="comment-edit-container relative">
                                        <textarea autocomplete="off" class="editable-input comment-editable-text"></textarea>
                                        <div class="close-edit"></div>
                                    </div>
                                </div>
                            </div>
                            $comment_options
                        </div>
                        <div class="row-v-flex underneath-comment-buttons-container">
                            <a href="" class="link-style-3">like</a>
                            <a href="" class="link-style-3">reply</a>
                            <div style="margin-left: 6px">
                                <p class="regular-text-style-2"> . <span class="time-of-comment">$comment_life</span></p>
                            </div>
                        </div>
                    </div>
                </div>
COM;
        }

        public function generate_post_image($url) {
            return <<<PI
                <div class="post-media-item-container relative">
                    <img src="$url" class="post-media-image" alt="">
                    <div class="post-view-button"></div>
                </div>
PI;
        }

        function is_dir_empty($dir) {
            return (count(glob("$dir/*")) === 0); // empty
        }
    }

?>