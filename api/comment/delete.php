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

if(!isset($_POST["comment_id"])) {
    echo json_encode(array(
        "message"=>"comment id required !",
        "success"=>false
    ));
}

$post_id = sanitize_id($_POST["comment_id"]);

$comment = new Comment();
$comment->set_property("id", $post_id);
if($comment->delete()) {
    echo 1;
} else {
    echo -1;
}