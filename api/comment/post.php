<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use classes\DB;
use models\{User, Comment};
use layouts\post\Post as Post_Manager;

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

if(!isset($_POST["current_user_id"])) {
    echo json_encode(array(
        "message"=>"Current user id required !",
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
$current_user_id = sanitize_id($_POST["current_user_id"]);

$comment = new Comment();
$comment->setData(array(
    "comment_owner"=>$comment_owner,
    "post_id"=>$post_id,
    "comment_text"=>$comment_text
));
$comment = $comment->add();
// Right now, we don't now the id of added comment
$captured_id = DB::getInstance()->query("SELECT id FROM comment WHERE comment_owner = ? AND comment_date = ?", array(
    "comment_owner"=>$comment->get_property("comment_owner"),
    "comment_date"=>$comment->get_property("comment_date")
))->results()[0]->id;

$comment->set_property("id", $captured_id);
    

$post_manager = Post_Manager::generate_comment($comment, $current_user_id);

echo $post_manager;

/* you can only add and return the following array, but we want to return a comment component
echo json_encode(array(
    "message"=>"Comment added successfully !",
    "success"=>true
));*/