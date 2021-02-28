<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use classes\{Config, Validation, Hash, Token, Common, DB};
use models\{User, Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST, FILES");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";
require_once "../../functions/get_extension.php";

/* First we check if the tokens matches, then we check if owner of the post exists, if so we sanitize
   All the necessary data comming to us and we check the image uploaded (IN THIS CASE WE WORK ON ONLY ONE IMAGE)
   then we see if validator passed, then we need to see if the owner is exists in database
*/

if(Token::check(Common::getInput($_POST, "token_post"), "share-post")) {
    $validator = new Validation();
    if(isset($_POST["post_owner"])) {
        $id = sanitize_id($_POST["post_owner"]);
        // Sanitize id used here because it has the same task to perform
        $post_visibility = sanitize_id($_POST["post-visibility"]);
        $post_place = sanitize_id($_POST["post-place"]);

        // CHECK IF POST_VISIBILITY ID IS THERE IN THE DB
        DB::getInstance()->query("SELECT * FROM post_visibility WHERE id = ?", array($post_visibility));
        if(DB::getInstance()->count() > 0) {
            $post_visibility = $post_visibility;
        } else {
            $post_visibility = 1;
        }

        // CHECK IF POST_PLACE ID IS THERE IN THE DB
        DB::getInstance()->query("SELECT * FROM post_place WHERE id = ?", array($post_place));
        if(DB::getInstance()->count() > 0) {
            $post_place = $post_place;
        } else {
            $post_place = 1;
        }

        $text_content = sanitize_text($_POST["post-textual-content"]);

        $supported_video_extensions = array(".mp4", ".mov", ".wmv", ".flv", ".avi", ".avchd", ".webm", "mkv");
        $supported_image_extensions = array(".png", ".jpg", ".jpeg", ".gif");

        if(!empty($_FILES)) {
            foreach($_FILES as $file) {
                $fileName = $file["name"];
                $ext = strtolower(get_extension($fileName));

                if(in_array($ext, $supported_video_extensions)) {
                    $validator->check($_FILES, array(
                        $file["name"]=>array(
                            "name"=>"Video",
                            "video"=>"video"
                        )
                    ));
                } else if(in_array($ext, $supported_image_extensions)) {
                    $validator->check($_FILES, array(
                        $file["name"]=>array(
                            "name"=>"Picture",
                            "image"=>"image"
                        )
                    ));
                }
            }
        }

        if($validator->passed()) {
            $user_id_exists = User::user_exists("id", $id);

            if($user_id_exists) {
                $user = new User();
                $user->fetchUser("id", $id);

                // Create unique id for post
                $post_id = uniqid('', true);

                $post = new Post();
                $post->setData(array(
                    "post_owner"=> $id,
                    "post_visibility"=> $post_visibility,
                    "post_place"=> $post_place,
                    "post_date"=> date("Y/m/d H:i:s"),
                    "text_content"=> $text_content,
                    "picture_media"=>"data/users/" . $user->getPropertyValue("username") . "/posts/$post_id/media/pictures/",
                    "video_media"=>"data/users/" . $user->getPropertyValue("username") . "/posts/$post_id/media/videos/",
                ));

                $user_posts_path = "../../data/users/" . $user->getPropertyValue("username") . "/posts";
                
                if(isset($_FILES) && !empty($_FILES)) {
                    createPostFolders($user_posts_path, $post_id);
                }

                $post_images_dir = $user_posts_path . "/" . $post_id . "/media/pictures/";
                $post_videos_dir = $user_posts_path . "/" . $post_id . "/media/videos/";

                foreach($_FILES as $asset) {
                    $fileName = $asset["name"];
                    $ext = strtolower(get_extension($fileName));

                    $file = $asset["name"];
                    $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);
                    // Generate post name
                    $generatedName = Hash::unique();
                    if(in_array($ext, $supported_video_extensions)) {
                        $targetFile = $post_videos_dir . $generatedName . $original_extension;
                    } else if(in_array($ext, $supported_image_extensions)) {
                        $targetFile = $post_images_dir . $generatedName . $original_extension;
                    }
                    $generatedName = htmlspecialchars($generatedName);

                    // Here we need to loop through the uploaded files if there are many and store them in trheir directories
                    if(!empty($asset["name"])) {
                        if(move_uploaded_file($asset["tmp_name"], $targetFile)) {
                            /*
                                We don't have to save image path to picture media because it could be more than one image
                                per post so we just save the picture directory so that we can fetch all images
                            */
                        } else {
                            $validator->addError("Sorry, there was an error uploading your post picture.");
                        }
                    }
                }
                
                $post->add();
            } else {
                // Print error or store it in a variable to sown later, then return false
                $validator->addError("Sorry, the poster doesn not exist !");
            }
        } else {

        }
    }

    if($validator->passed()) {
        return true;
    } else {
        return $validator->errors();
    }
}

function createPostFolders($user_posts_path, $post_id) {
    if(!file_exists($user_posts_path)) {
        mkdir($user_posts_path, 0777, true);
    }

    mkdir($user_posts_path . "/$post_id", 0777, true);
    mkdir($user_posts_path . "/$post_id/media", 0777, true);
    mkdir($user_posts_path . "/$post_id/media/pictures", 0777, true);
    mkdir($user_posts_path . "/$post_id/media/videos", 0777, true);
}