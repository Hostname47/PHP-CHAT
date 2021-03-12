<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";

if(!isset($_POST["post_id"])) {
    echo json_encode(array(
        "message"=>"post id required !",
        "success"=>false
    ));
}

if(!isset($_POST["new_post_text"])) {
    echo json_encode(array(
        "message"=>"new post text required !",
        "success"=>false
    ));
}

$post_id = sanitize_id($_POST["post_id"]);
$new_post_text = sanitize_text($_POST["new_post_text"]);

$post = new Post();
$post->fetchPost($post_id);
$post->set_property('text_content', $new_post_text);

if($post->update()) {
    echo $new_post_text;
} else {
    echo -1;
}