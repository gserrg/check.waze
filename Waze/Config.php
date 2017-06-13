<?php

namespace Waze;

class Config
{
	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function get($name)
	{
		$file = '../config/' . $name . '.php';
		if (!file_exists($file)) {
			return array();
		}
		/** @noinspection PhpIncludeInspection */
		return include($file);
	}
}
