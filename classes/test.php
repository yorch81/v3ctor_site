<?php
require "V3Site.class.php";
require "../config.php";

$app =  new V3Site($admin, $password, $dir);

if ($app->checkAvailability("yorchi"))
	$app->makeApp("yorchi");
else
	echo "Application Name not Available\n";
?>