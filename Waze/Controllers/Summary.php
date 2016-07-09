<?php

namespace Waze\Controllers;

use Waze\Controller;
use Waze\DB;
use Waze\TestsFactory;

class Summary extends Controller
{
	public function process()
	{
		$areas = DB::getInstance()->as_array('SELECT areas_mapraid.name, areas_mapraid.id, updates.updated_at
			FROM areas_mapraid
			LEFT JOIN states_shapes ON(areas_mapraid.id = states_shapes.id_1)
			LEFT JOIN updates ON(states_shapes.hasc_1 = updates.object) ORDER BY areas_mapraid.name');
		$tests = TestsFactory::getInstance();
		$this->layout([
			'css' => [
				'/builds/site.css' => '',
			],
			'title' => 'check.waze - сводная информация по ошибкам',
		]);
		return $this->render('summary', array(
			'areas' => $areas,
			'test' => $tests,
		));
	}
}
