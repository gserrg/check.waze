<?php

namespace Waze\Controllers;

use Waze\Controller;
use Waze\Config;

class Boxer extends Controller
{
	public function process()
	{
		$regions = Config::get('bbox');
		$boxes = $regions[strtoupper($this->params['code'])];
		$coordinates = [];
		foreach ($boxes as $box) {
			$coordinates[] = implode(',', $box);
		}
		$this->layout([
			'js' => [
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyCX9tzYywsfi6uB1KvBN3CGRl3e3S-QBtg&signed_in=true&callback=initMap' => 'async defer',
				'/builds/g-map.js' => '',
			],
			'css' => ['/builds/site.css' => '']
		]);
		return $this->render('g-map', [
			'coordinates' => implode("\n", $coordinates),
		]);
	}
}