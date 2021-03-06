<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{Comment};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";

if(!isset($_POST["comment_id"])) {
    echo json_encode(array(
        "message"=>"comment id required !",
        "success"=>false
    ));
}

if(!isset($_POST["new_comment"])) {
    echo json_encode(array(
        "message"=>"new comment required !",
        "success"=>false
    ));
}

$comment_id = sanitize_id($_POST["comment_id"]);
$new_comment = sanitize_text($_POST["new_comment"]);

$comment = new Comment();
$comment->fetch_comment($comment_id);

$comment->set_property("comment_text", $new_comment);

if($comment->update()) {
    echo $new_comment;
} else {
    echo -1;
}