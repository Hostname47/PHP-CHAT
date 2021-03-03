<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

if(!isset($_POST["post_id"])) {
    echo json_encode(array(
        "message"=>"post id required !",
        "success"=>false
    ));
    return;
}
if(!isset($_POST["post_owner"])) {
    echo json_encode(array(
        "message"=>"post owner id required !",
        "success"=>false
    ));
    return;
}

$post_id = sanitize_id($_POST["post_id"]);
$post_owner = sanitize_id($_POST["post_owner"]);

if(Post::exists($post_id)) {
    /*
        We first need to make shure that the post exists with that id, then we need to check if the user
        who makes the request is the owner of the post because we only allow the owner of the post to delete it.
    */
    if($post_owner == Post::get_post_owner($post_id)->post_owner) {
        $post = new Post();
        $post->set_property('post_id', $post_id);
    
        $post->delete();
        echo json_encode(array(
            "success"=>true,
            "message"=>'post deleted successfully !'
        ));
    } else {
        echo json_encode(array(
            "success"=>false,
            "message"=>'user\'s id is invalide!'
        ));
    }

} else {
    echo json_encode(array(
        "success"=>false,
        "message"=>'post not exists'
    ));
}

