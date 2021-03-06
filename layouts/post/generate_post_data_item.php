<?php

    include_once "../../view/general/CreatePost.php";

    header('Access-Control-Allow-Origin: *');

    use layouts\general\CreatePost;
    
    $resource = (isset($_GET["resource"])) ? $_GET["resource"] : "";
    $cp = new CreatePost();

    return $cp->generatePostCreationImage();

?>