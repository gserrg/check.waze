<?php
namespace Waze;

include_once 'DB.php';

class TestsFactory
{
	private $db;
	private $test;
	private $templates;

	public function __construct()
	{
		$this->db = DB::instance();
		$this->test = include('../config/tests.php');
		$this->templates = include('fields_templates.php');
	}

	public function display($area)
	{
		$render = '';
		foreach ($this->test as $test_name => $config) {
			if (isset($config['sql'], $config['fields'])) {
				$sql = str_replace(':area_id' , $area, $config['sql']);
				$list = $this->db->as_array($sql);
				if (count($list)) {
					$params = $config;
					unset($params['sql']);
					$params['list'] = $list;
					$params['name'] = $test_name;
					$params['templates'] = $this->templates;
					$render .= $this->render($params);
				}
			}
		}
		return $render;
	}

	private function render($params)
	{
		ob_start();
		extract($params);
		include ('../render/test.phtml');
		$render = ob_get_contents();
		ob_clean();
		return $render;
	}
}
