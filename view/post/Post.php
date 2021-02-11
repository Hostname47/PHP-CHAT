<?php

    namespace view\post;

    use classes\{Config};
    use models\{User, Comment, Post as Pst};

    class Post {

        function generate_post($post, $user) {
            $current_user_id = $user->getPropertyValue("id");
            $current_user_picture = $user->getPropertyValue("picture");

            $post_owner_user = new User();
            $post_owner_user->fetchUser("id", $post->get_property("post_owner"));

            $post_owner_picture = Config::get("root/path") . (($post_owner_user->getPropertyValue("picture") != "") ? $post_owner_user->getPropertyValue("picture") : "assets/images/logos/logo512.png");
            
            $post_id= $post->get_property("post_id");
            $post_owner_name = $post_owner_user->getPropertyValue("firstname") . " " . $post_owner_user->getPropertyValue("lastname") . " -@" . $post_owner_user->getPropertyValue("username");

            $post_date = $post->get_property("post_date");
            $post_date = date("F d \a\\t Y h:i A",strtotime($post_date)); //January 9 at 1:34 PM

            $post_owner_profile = Config::get("root/path") . "profile.php?username=" . $post_owner_user->getPropertyValue("username");

            $root = Config::get("root/path");
            $project_name = Config::get("root/project_name");
            $project_path = $_SERVER['DOCUMENT_ROOT'] . "/" . $project_name . "/";

            $post_images_location = $post->get_property("picture_media");
            $post_videos_location = $post->get_property("video_media");

            $post_images_dir = $project_path . $post->get_property("picture_media");
            $post_videos_dir = $project_path . $post->get_property("video_media");

            $post_text_content = htmlspecialchars_decode($post->get_property("text_content"));

            $image_components = "";
            $video_components = "";
            if(is_dir($post_images_dir)) {
                if($this->is_dir_empty($post_images_dir) == false) {
                    $fileSystemIterator = new \FilesystemIterator($post_images_dir);
                    foreach ($fileSystemIterator as $fileInfo){
                        $image_components .= $this->generate_post_image($root . $post_images_location . $fileInfo->getFilename());
                    }
                }
            }

            if(is_dir($post_videos_dir)) {
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
                        $video_components
                        $image_components
                    </div>
                    <div class="react-on-opost-buttons-container">
                        <a href="" class="white-like-back post-bottom-button">Like</a>
                        <a href="" class="white-comment-back post-bottom-button">Comment</a>
                        <a href="" class="reply-back post-bottom-button">Share</a>
                    </div>
                    <div class="comment-section">
                        $comments_components
                        <div class="class="owner-comment"">
                            <div class="comment-block">
                                <div>
                                    <img src="$current_user_picture" class="image-style-9" alt="">
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
                (empty($comment_owner->getPropertyValue("picture")) 
                ? "assets/images/logos/logo512.png" : $comment_owner->getPropertyValue("picture"));
            $comment_owner_username = $comment_owner->getPropertyValue("username");
            $comment_text = $comment->get_property("comment_text");
            $comment_id = $comment->get_property("id");

            $now = strtotime("now");
            $seconds = floor($now - strtotime($comment->get_property("comment_date")));
            
            if($seconds > 29030400) {
                $comment_life = floor($seconds / 29030400) . "y";
            } else if($seconds > 2419200) {
                $comment_life = floor($seconds / 604800) . "w";
            } else if($seconds < 604799 && $seconds > 86400) {
                $comment_life = floor($seconds / 86400) . "d";
            } else if($seconds < 86400 && $seconds > 3600) {
                $comment_life = floor($seconds / 3600) . "h";
            } else if($seconds < 3600 && $seconds > 60) {
                $comment_life = floor($seconds / 60) . "min";
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
            */
            
            $comment_options = <<<CO
    <div class="relative comment">
        <div class="comment-options-container button-with-suboption"></div>
        <div class="sub-options-container sub-options-container-style-2" style="z-index: 1; width: 129px; top: 20px; left: -100px">
            <div class="options-container-style-1 black">
CO;

            $owner_of_post_contains_current_comment = $comment->get_property('post_id');
            // We use Pst as Post model alias cauz we alsready have Post view manager in use
            $owner_of_post_contains_current_comment = Pst::get_post_owner($owner_of_post_contains_current_comment);
            if(($comment->get_property("comment_owner") == $current_user_id)
                || $current_user_id == $owner_of_post_contains_current_comment->post_owner)
            {
                $comment_options .= <<<CO
                <div class="sub-option-style-2">
                    <a href="" class="black-link delete-comment">Delete comment</a>
                </div>
CO;
            }

            $comment_options .= <<<CO
            <div class="sub-option-style-2">
                <a href="" class="black-link reply-comment-button">Reply</a>
            </div>
        </div>
    </div>
</div>
CO;

            return <<<COM
                <div class="comment-block">
                    <input type="hidden" class="comment_id" value="$comment_id">
                    <div>
                        <img src="$comment_owner_picture" class="image-style-9" alt="">
                    </div>
                    <div>
                        <div class="row-v-flex">
                            <div class="comment-wrapper">
                                <div>
                                    <a href="" class="comment-owner">$comment_owner_username</a>
                                    <p class="comment-text">$comment_text</p>
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