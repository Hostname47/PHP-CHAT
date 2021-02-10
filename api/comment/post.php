<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, Comment};
use view\post\Post as Post_Manager;

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

if(!isset($_POST["comment_owner"])) {
    echo json_encode(array(
        "message"=>"comment owner required !",
        "success"=>false
    ));
}

if(!isset($_POST["comment_owner"]) || empty($_POST["comment_owner"])) {
    echo json_encode(array(
        "message"=>"comment should not be empty or unset !",
        "success"=>false
    ));
}

$comment_owner = sanitize_id($_POST["comment_owner"]);
$post_id = sanitize_id($_POST["post_id"]);
$comment_text = sanitize_text($_POST["comment_text"]);

$comment = new Comment();
$comment->setData(array(
    "comment_owner"=>$comment_owner,
    "post_id"=>$post_id,
    "comment_text"=>$comment_text
));
$comment = $comment->add();

$post_manager = Post_Manager::generate_comment($comment);

echo $post_manager;

/* you can only add and return the following array, but we want to return a comment component
echo json_encode(array(
    "message"=>"Comment added successfully !",
    "success"=>true
));*/