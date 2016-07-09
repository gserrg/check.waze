<?php

	function __autoload($class) {
		$class = '../' . str_replace("\\", '/', $class) . '.php';
		/** @noinspection PhpIncludeInspection */
		require_once($class);
	}

	$controller = Waze\Router::process();
	echo $controller->process();
