<?php

namespace Waze\Controllers;

use Waze\Controller;
use Waze\DB;

class Index extends Controller
{
	public function process()
	{
		//$tests = new TestsFactory();
		$areas = DB::getInstance()->as_array('SELECT areas_mapraid.name, areas_mapraid.id, updates.updated_at
		FROM areas_mapraid
		LEFT JOIN states_shapes ON(areas_mapraid.id = states_shapes.id_1)
		LEFT JOIN updates ON(states_shapes.hasc_1 = updates.object) ORDER BY areas_mapraid.name');
		$this->layout([
			'css' => [
				'/builds/bootstrap.min.css' => 'media="all" data-turbolinks-track="true"',
				'/builds/site.css' => '',
			],
		]);
		return $this->render('index', array(
			'areas' => $areas,
		));
	}
}
