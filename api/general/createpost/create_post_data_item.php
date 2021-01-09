<?php

    include_once "../../../view/general/CreatePost.php";

    header('Access-Control-Allow-Origin: *');

    use view\general\CreatePost;
    
    $resource = (isset($_GET["resource"])) ? $_GET["resource"] : "";
    $cp = new CreatePost();

    return $cp->generatePostCreationImage();

?>