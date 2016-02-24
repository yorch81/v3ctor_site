<?php
require "V3Site.class.php";

$admin = "root";
$password = "r00tm0ng0";
$dir = "/home/yorch/Projects/php/apps/";

$app =  new V3Site($admin, $password, $dir);

$app->makeApp("v3demo");

?>