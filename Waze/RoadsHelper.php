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
		if (empty($data['str_name']) || $data['str_name'] == false) {
			$street = 'Без улицы';
		} else {
			$street = $data['str_name'];
		}
		if (empty($data['str_city_id'])) {
			$city = 'без города';
		} else {
			if (empty($data['c_name']) || $data['c_isempty'] == false) {
				$city = 'без города';
			} else {
				$city = $data['c_name'];
			}
		}
		return $street . ', ' . $city;
	}
}
