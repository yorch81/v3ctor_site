<?php
require "V3Site.class.php";
require "../config.php";

$app =  new V3Site($admin, $password, $dir);

$app->makeApp("yorchi");

?>