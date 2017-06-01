<?php

	include('../vendor/autoload.php');

	$controller = Waze\Router::process();
	echo $controller->process();
