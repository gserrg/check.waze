<?php

namespace Waze\Controllers;

use Waze\Controller;
use Waze\DB;
use Waze\TestsFactory;

class TestBetta extends Controller
{
	public function process()
	{
		$area_code = strtoupper($this->params['area_code']);
		$area_name = DB::getInstance()->as_array("SELECT title as name FROM regions WHERE mnemocode = '{$area_code}'");
		if(count($area_name) != 1) {
			return '';
		}
		$this->layout([
			'css' => [
				'/builds/site.css' => '',
			],
			'title' => 'check.waze - ' . $area_name[0]['name'],
			'js' => [
				'https://code.jquery.com/jquery-2.2.4.min.js' => 'integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"',
				'//cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js' => '',
				'/builds/bootstrap.min.js' => 'data-turbolinks-track="true"',
				'/builds/filter.js' => '',
			],
		]);
		return $this->render('area', array(
			'area_id' => $area_code,
			'tests' => TestsFactory::getInstance(['betta' => 'true']),
			'editor_level' => isset($_COOKIE['editor_level']) ? $_COOKIE['editor_level'] : 6,
		));
	}
}
