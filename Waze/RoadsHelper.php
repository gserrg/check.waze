<?php
namespace Waze;


class RoadsHelper extends Singleton
{
	private $types;
	private $db;

	public function init()
	{
		$this->types = Config::get('types');
		$this->db = DB::getInstance();
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function type($id)
	{
		if (!isset($this->types[$id])) {
			return 'unknown';
		}
		return isset($this->types[$id]['name']) ? $this->types[$id]['name'] : $this->types[$id]['title'];
	}

	/**
	 * @param string[] $data
	 *
	 * @return string
	 */
	public function name($data)
	{
		if ($data['street_id'] == 0) {
			return 'Безымянный (' . $data['id'] . ')';
		}
		return $this->nameStreet($data) . ', ' . $this->nameCity($data);
	}

	/**
	 * @param string[] $data
	 *
	 * @return string
	 */
	public function nameCity($data)
	{
		if ($data['street_id'] == 0) {
			return '#' . $data['id'] . " [{$data['st_name']}]";
		}
		if (empty($data['str_city_id'])) {
			return '#без города' . " [{$data['st_name']}]";
		} else {
			if (empty($data['c_name']) || $data['c_isempty'] == false) {
				return '#без города' . " [{$data['st_name']}]";
			} else {
				$name = $data['c_name'];
				if(isset($_COOKIE['visible_region']) && $_COOKIE['visible_region'] == 'yes') {
					$name .= " [{$data['st_name']}]";
				}
				return $name;
			}
		}
	}

	/**
	 * @param string[] $data
	 *
	 * @return string
	 */
	public function nameStreet($data)
	{
		if ($data['street_id'] == 0) {
			return '#' . $data['id'];
		}
		if (empty($data['str_name']) || $data['str_name'] == false) {
			return '#без улицы';
		} else {
			return $data['str_name'];
		}
	}
}
