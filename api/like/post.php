<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, Like};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

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

$post_id = sanitize_id($_POST["post_id"]);
$current_user_id = sanitize_id($_POST["current_user_id"]);

$like = new Like();
$like->setData(array(
    "post_id"=>$post_id,
    "user_id"=>$current_user_id,
));
$res = $like->add();

/*
    1: added successfully
    2: deleted successfully
    -1: there's a problem
*/
if($res == 1) {
    echo 1;
} else if ($res == -1) {
    if($like->delete()) {
        echo 2;
    }
} else {
    echo -1;
}

/* you can only add and return the following array, but we want to return a comment component
echo json_encode(array(
    "message"=>"Comment added successfully !",
    "success"=>true
));*/