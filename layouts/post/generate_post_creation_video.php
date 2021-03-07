<?php

    include_once "../../layouts/general/CreatePost.php";

    header('Access-Control-Allow-Origin: *');

    use layouts\general\CreatePost;
    
    $cp = new CreatePost();

    echo $cp->generatePostCreationVideo();

?>