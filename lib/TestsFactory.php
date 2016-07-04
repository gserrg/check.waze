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

	public function area($area_id)
	{
		$render = '';
		foreach ($this->test as $test_name => $config) {
			if (isset($config['sql'], $config['fields'])) {
				$list = $this->db->get_segments($config['sql'], $area_id);
				if ($list !== false && count($list)) {
					$params = $config;
					unset($params['sql']);
					$params['list'] = $list;
					$params['name'] = $test_name;
					$params['templates'] = $this->templates;
					$render .= $this->render_area($params);
				}
			}
		}
		return $render;
	}

	private function render_area($params)
	{
		ob_start();
		extract($params);
		include ('../render/area-test.phtml');
		$render = ob_get_contents();
		ob_clean();
		return $render;
	}

	public function index($area_id)
	{
		$render = '';
		foreach ($this->test as $test_name => $config) {
			if (isset($config['sql'], $config['fields'])) {
				$count = $this->db->get_segments_count($config['sql'], $area_id);
				if ($count !== false) {
					$render .= $this->render_count(array(
						'title' => $config['title'],
						'count' => $count));
				}
			}
		}
		return $render;
	}

	private function render_count($params)
	{
		ob_start();
		extract($params);
		include ('../render/index-test.phtml');
		$render = ob_get_contents();
		ob_clean();
		return $render;
	}
}
