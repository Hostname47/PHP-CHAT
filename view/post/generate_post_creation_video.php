<?php

    include_once "../../view/general/CreatePost.php";

    header('Access-Control-Allow-Origin: *');

    use view\general\CreatePost;
    
    $cp = new CreatePost();

    echo $cp->generatePostCreationVideo();

?>