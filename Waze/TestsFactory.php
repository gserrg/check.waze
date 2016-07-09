<?php

namespace Waze;

class TestsFactory extends Singleton
{
	/** @var DB $db */
	private $db;
	private $test;
	private $templates;

	public function init()
	{
		$this->db = DB::getInstance();
		$this->test = Config::get('tests');
		$this->templates = Config::get('fields_templates');
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
		include('../render/area-test.phtml');
		$render = ob_get_contents();
		ob_clean();
		return $render;
	}

	public function summary($area_id)
	{
		$render = '';
		foreach ($this->test as $test_name => $config) {
			if (isset($config['sql'], $config['fields'])) {
				$count = $this->db->get_segments_count($config['sql'], $area_id);
				if ($count !== false) {
					$render .= $this->render_count(array(
						'title' => $config['title'],
						'count' => $count,
					));
				}
			}
		}
		return $render;
	}

	private function render_count($params)
	{
		ob_start();
		extract($params);
		include('../render/summary-test.phtml');
		$render = ob_get_contents();
		ob_clean();
		return $render;
	}
}
