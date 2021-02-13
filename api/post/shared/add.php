<?php


require_once "../../../vendor/autoload.php";
require_once "../../../core/rest_init.php";

use models\{User, Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../../functions/sanitize_id.php";

if(!isset($_POST["post_owner"])) {
    echo json_encode(array(
        "message"=>"post owner id required !",
        "success"=>false
    ));
}

if(!isset($_POST["post_visibility"])) {
    echo json_encode(array(
        "message"=>"post_visibility required !",
        "success"=>false
    ));
}

if(!isset($_POST["post_place"])) {
    echo json_encode(array(
        "message"=>"post_place required !",
        "success"=>false
    ));
}

if(!isset($_POST["post_shared_id"])) {
    echo json_encode(array(
        "message"=>"post shared id required !",
        "success"=>false
    ));
}

$post_owner = sanitize_id($_POST["post_owner"]);
$post_visibility = is_numeric($_POST["post_visibility"]) ? $_POST["post_visibility"] : 1;
$post_place = is_numeric($_POST["post_place"]) ? $_POST["post_place"] : 1;
$post_shared_id = sanitize_id($_POST["post_shared_id"]);

/*
    Text cotent is not necessary because we make edit later; so that the user when he shares the post then he could edit it 
    and add his own text content. We set photo and video directories to null because we don't want them in shared post; 
    In shared post the user could only change text, post visibility, or post place
    We set is_shared to 1 because it's a shared post and we set the original_post to post_shared_id
*/

$post = new Post();
$post->setData(array(
    "post_owner"=> $post_owner,
    "post_visibility"=> $post_visibility,
    "post_place"=> $post_place,
    "text_content"=> "",
    "picture_media"=>null,
    "video_media"=>null,
    "is_shared"=>1,
    "post_shared_id"=>$post_shared_id
));

$res = $post->add();

if($res) {
    echo 1;
} else {
    echo -1;
}