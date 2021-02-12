<?php


require_once "../../../vendor/autoload.php";
require_once "../../../core/rest_init.php";

use models\{User, Shared_Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../../functions/sanitize_id.php";

if(!isset($_POST["post_id"])) {
    echo json_encode(array(
        "message"=>"post id required !",
        "success"=>false
    ));
}

if(!isset($_POST["poster_id"])) {
    echo json_encode(array(
        "message"=>"poster id required !",
        "success"=>false
    ));
}

$post_id = sanitize_id($_POST["post_id"]);
$poster_id = sanitize_id($_POST["poster_id"]);

$shared_post = new Shared_Post();
$shared_post->setData(array(
    "post_id"=>$post_id,
    "poster_id"=>$poster_id,
));
$res = $shared_post->add();

if($res) {
    echo 1;
} else {
    echo -1;
}