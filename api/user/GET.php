<?php

include_once "../../vendor/autoload.php";
require_once "../../core/init.php";

use classes\{DB, Config};
use models\User;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$user = new User();
// Id should be set and numeric and should be there in the database, otherwise error message will be shown
if(isset($_GET["id"]) && 
    is_numeric($id=$_GET["id"]) && 
    $user->fetchUser("id", $id)) {
    echo json_encode($user);
} else {
    echo json_encode(array(
        'problem'=>'Invalid data provided or the id is not within our database'
    ));
}
