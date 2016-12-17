<?php
require "V3Site.class.php";
require "../config.php";

$app =  new V3Site($admin, $password, $dir);

if ($app->checkAvailability("uat"))
	$app->makeApp("uat");
else
	echo "Application Name not Available\n";
?>