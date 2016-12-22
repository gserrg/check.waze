<?php

namespace Waze\Controllers;

use Waze\Controller;
use Waze\DB;

class Map extends Controller
{
	public function process()
	{
		$areas = DB::getInstance()->as_array('SELECT ST_AsText(areas_mapraid.geom), areas_mapraid.name, areas_mapraid.id FROM areas_mapraid');
		$list = array();
		foreach ($areas as $area) {
			$tmp = $this->parse($area);
			if ($tmp) {
				$list[] = '{'.
					'color:"' . $this->titleColor($area['name']) . '", ' .
					'name:"' . $area['name'] . '", ' .
					'id:' . $area['id'] . ', ' .
					'poly:' . $tmp .
				'}';
			}
		}
		$this->layout([
			'js' => [
				'https://api-maps.yandex.ru/2.0/?load=package.standard,package.geoObjects,&lang=ru-RU' => '',
				'/builds/y-map.js' => '',
			],
			'css' => ['/builds/site.css' => '']
		]);
		return $this->render('y-map', [
			'raw_coordinates' => '[' . implode(',', $list) . ']',
		]);
	}

	private function parse($area)
	{
		$string = $area['st_astext'];
		$string = ltrim($string, 'MULTIPOLYGON(((');
		$string = rtrim($string, ')))');
		$out = [];
		if (false !== strpos($string, ')),((')) {
			$string = str_replace(')),((', '),(', $string);
			$polygons = explode('),(', $string);
		} else if (false !== strpos($string, '),(')) {
			$polygons = explode('),(', $string);
		} else {
			$polygons = [$string];
		}
		foreach ($polygons as $polygon) {
			$points = explode(',', $polygon);
			$new_polygons = [];
			foreach ($points as $point) {
				$new_pounts = explode(' ', $point);
				$new_polygons[] = $new_pounts[1] . ', ' . $new_pounts[0];
			}
			$out[] = '[' . implode('], [', $new_polygons) .']';
		}
		return '[[' . implode('], [', $out) . ']]';
	}

	private function titleColor($name) {
		$md5 = md5($name);
		return '#' . substr($md5, 0, 6);
	}

}