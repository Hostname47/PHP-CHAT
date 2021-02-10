<?php

    namespace view\post;

    use classes\{Config};
    use models\User;

    class Post {

        function generate_post($post, $user) {
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
                                <img src="$" class="image-style-9" alt="">
                            </div>
                            <div class="comment-input-form-wrapper">
                                <form action="" method="POST" class="comment-form relative">
                                    <input type="text" name="comment" placeholder="Write a comment .." class="comment-style">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="pid" value="$post_id">
            </div>

EOS;
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