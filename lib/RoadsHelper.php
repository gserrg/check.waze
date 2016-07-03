<?php
namespace Waze;

include_once 'DB.php';

class RoadsHelper
{
	private $types;
	private static $instance;
	private $db;

	/**
	 * RoadsHelper constructor.
	 *
	 //* @param int $region
	 */
	public function __construct(/*$region*/)
	{
		$this->types = [
			3 => 'freeway',
			6 => 'major-highway',
			7 => 'minor-highway',
			4 => 'Ramp',
			2 => 'primary-street',
			1 => 'street',
			8 => 'dirt-road',
			20 => 'parking-lot-road',
			17 => 'private-road',
			5 => 'walking-trail',
			10 => 'pedestrian-boardwalk',
			16 => 'stairway',
			18 => 'railroad',
			19 => 'runway',
			15 => 'ferry',
		];
		self::$instance = $this;
		$this->db = DB::instance();
	}

	/**
	 * @return $this
	 */
	public static function instance()
	{
		if (self::$instance === null) {
			new self;
		}
		return self::$instance;
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
		return $this->types[$id];
	}

	/**
	 * @param string[] $data
	 *
	 * @return string
	 */
	public function name($data)
	{
		if ($data['street_id'] == 0) {
			return 'Безымянный';
		}
		$street_info = $this->db->as_array('SELECT * FROM streets where streets.id = ' . $data['street_id']);
		if (empty($street_info['name']) || $street_info['isempty'] == false) {
			$street = 'Без улицы';
		} else {
			$street = $street_info['name'];
		}
		if (empty($street_info['city_id'])) {
			$city = 'без города';
		} else {
			$city_info = $this->db->as_array('select cities.name, cities.isempty FROM cities WHERE cities.id = ' . $street_info['city_id']);
			if (empty($city_info['name']) || $city_info['isempty'] == false) {
				$city = 'без города';
			} else {
				$city = $city_info['name'];
			}
		}
		return $street . ', ' . $city;
	}
}
