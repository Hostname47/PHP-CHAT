<?php

namespace view\general;

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
    }
?>