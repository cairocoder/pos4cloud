<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("_inc/header.php");
require_once("_inc/privilegedUser.php");
require_once("_inc/Role.php");

?>

<h1>Test</h1>

<?php

var_dump($_SESSION);
$role = Role::getRolePerms($_SESSION['role_id']);
var_dump($role);