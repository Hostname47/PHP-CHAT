<?php

namespace layouts\general;

    class CreatePost {
        public static function generatePostCreationImage() {
            // Path will be set in src in javascript file when the user upload a file
            echo <<<EOS
            <div class="relative post-creation-item">
                <div class="delete-uploaded-item absolute"></div>
                <img src="" class="image-post-uploaded" alt="">
                <input type="hidden" class="pciid" value="">
            </div>
EOS;
        }

        public static function generatePostCreationVideo() {
            // Path will be set in src in javascript file when the user upload a file
            echo <<<EOS
            <div class="relative post-creation-item">
                <div class="delete-uploaded-item absolute"></div>
                <img src="" class="video-post-thumbnail" alt="">
                <input type="hidden" class="pciid" value="">
                <div class="post-creation-video-image-container">
                    <img src="public/assets/images/icons/videos.png" class="post-creations-video-image">
                </div>
                <div class="assets-pending">
                    <div class="pending-container relative">
                        <div class="pending-inner">
                            <div class="pendulum"></div>
                            <div class="pendulum-wrapper"></div>
                        </div>
                    </div>
                </div>
            </div>
EOS;
        }
    }
?>