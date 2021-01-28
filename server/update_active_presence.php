<?php
require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\DB;
use models\{User};

header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// We don't need any extra resource to come, we'll use the logged user $user to update his active_presence
$user->update_active();
