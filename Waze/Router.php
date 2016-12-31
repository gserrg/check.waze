<?php

namespace Waze;

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
		if ($request == '/settings/') {
			return new Controllers\Settings();
		}
		if ($request == '/map/') {
			return new Controllers\Map();
		}
		if ($request == '/boxer/') {
			return new Controllers\Boxer(['flag' => 'boxer',]);
		}
		if (preg_match('~^/boxer/(\w\w.\w\w)/$~', $request, $m)) {
			return new Controllers\Boxer([
				'code' => $m[1],
				'flag' => 'boxer',
			]);
		}
		if ($request == Config::get('git')) {
			system('cd ..; git pull');
			header('HTTP/1.0 404 Not Found');
			die;
		}
		if (preg_match('~/test_area/(\w\w.\w\w)/~', $request, $m)) {
			if (count($m) == 2) {
				return new Controllers\TestBetta([
					'area_code' => $m[1],
					'flag' => 'area',
				]);
			}
		}
		if (preg_match('~/test_area/(\d+)/~', $request, $m)) {
			if (count($m) == 2) {
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
