<?php

namespace Waze;


class DB
{
	private static $instance;

	/**
	 * DB constructor.
	 */
	public function __construct()
	{
		$c = include('../config/db.php');
		$dsn = 'host=' . $c['host'] . ' port=' . $c['port'] . ' user=' . $c['user'] . ' password=' . $c['password'] . ' dbname=' . $c['schema'];
		$this->connection = pg_connect($dsn) or die('Could not connect: ' . pg_last_error());
		self::$instance = $this;
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

	public function as_array($query)
	{
		$result = pg_query($query) or die(pg_last_error());
		return pg_fetch_all($result);
	}

	public function get_segments($params, $area_id) {
		$columns = isset($params['columns']) ? ', ' . $params['columns'] . ' ' : ' ';
		$join = isset($params['join']) ? $params['join'] : '';
		$where = isset($params['where']) ? '(' . $params['where'] . ') AND ' : '';
		$order = isset($params['order']) ? $params['order'] : 's.last_edit_on DESC';
		$limit = isset($params['limit']) ? $params['limit'] : 2000;
		$query = 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.last_edit_on, u.username as u_username, u.rank as u_rank, s.street_id, '
			.'str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty' . $columns
			. 'FROM segments AS s '
			. 'LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id) LEFT JOIN users as u on(u.id = s.last_edit_by) ' . $join
			.' WHERE ' . $where . ' area_id = ' . $area_id . ' ORDER BY ' . $order . ' LIMIT ' . $limit;
		return $this->as_array($query);
	}

	public function get_segments_count($params, $area_id) {
		$join = isset($params['join']) ? $params['join'] : '';
		$where = isset($params['where']) ? '(' . $params['where'] . ') AND ' : '';
		$query = 'SELECT count(*) ' . ' FROM segments AS s '
			. 'LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id) LEFT JOIN users as u on(u.id = s.last_edit_by) ' . $join
			.' WHERE ' . $where . ' area_id = ' . $area_id;
		$list = $this->as_array($query);
		$list = reset($list);
		return reset($list);
	}
}