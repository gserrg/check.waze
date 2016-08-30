<?php

namespace Waze\Controllers;

use Waze\Controller;
//use Waze\DB;

class Boxer extends Controller
{
	public function process()
	{
		//$areas = DB::getInstance()->as_array('SELECT ST_AsText(areas_mapraid.geom) FROM areas_mapraid
		//	LEFT JOIN states_shapes ON(areas_mapraid.id = states_shapes.id_1)
		//	LEFT JOIN updates ON(states_shapes.hasc_1 = updates.object)');
		$this->layout([
			'js' => [
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyCX9tzYywsfi6uB1KvBN3CGRl3e3S-QBtg&signed_in=true&callback=initMap' => 'async defer',
				'/builds/g-map.js' => '',
			],
			'css' => ['/builds/site.css' => '']
		]);
		return $this->render('g-map', [

		]);
	}
}