<?php

namespace Waze;

//use Waze\Controllers;

class Router
{
	public static function process()
	{
		$request = $_SERVER['REQUEST_URI'];
		if ($request == '/') {
			return new Controllers\Index();
		}
		if ($request == '/summary/') {
			return new Controllers\Summary();
		}
		if ($request == '/map/') {
			return new Controllers\Map();
		}
		if (preg_match('~/test_area/(\d+)/~', $request, $m)) {
			if(count($m) == 2) {
				return new Controllers\Test([
					'area_id' => $m[1],
					'flag' => 'area',
				]);
			}
		}
		header('HTTP/1.0 404 Not Found');
		die;
	}

}