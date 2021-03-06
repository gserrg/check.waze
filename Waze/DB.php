<?php

namespace Waze;

class DB extends Singleton
{
    protected $connection;

    public function init()
    {
        $c = Config::get('db');
        $dsn = 'host=' . $c['host'] . ' port=' . $c['port'] . ' user=' . $c['user'] . ' password=' . $c['password'] . ' dbname=' . $c['schema'];
        $this->connection = pg_connect($dsn) or die('Could not connect: ' . pg_last_error());
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {

    }

    public function as_array($query)
    {
        $result = pg_query($query) or die(pg_last_error());
        return pg_fetch_all($result);
    }

    public function get_segments_betta($params, $area_code)
    {
        $columns = isset($params['columns']) ? ', ' . $params['columns'] . ' ' : ' ';
        $join = isset($params['join']) ? $params['join'] : '';
        $where = isset($params['where']) ? '(' . $params['where'] . ') AND ' : '';
        if (isset($_COOKIE['editor_level'])) {
            $where .= '(s.lock <= ' . $_COOKIE['editor_level'] . ' or s.lock is null) AND ';
        }
        $order = isset($params['order']) ? $params['order'] : 's.last_edit_on DESC';
        $limit = isset($params['limit']) ? $params['limit'] : 5000;
        $query = 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.last_edit_on, u.username as u_username, u.rank as u_rank, s.street_id, '
            . 'str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty, st.name as st_name' . $columns
            . 'FROM segments AS s '
            . 'LEFT JOIN streets as str ON (s.street_id = str.id) '
            . 'LEFT JOIN cities as c ON (str.city_id = c.id) '
            . 'LEFT JOIN states as st ON (c.state_id = st.id) '
            . 'LEFT JOIN regions_ref ON (regions_ref.state = st.id)'
            . 'LEFT JOIN regions ON (regions_ref.region = regions.id)'
            . 'LEFT JOIN users as u on(u.id = s.last_edit_by) ' . $join
            . ' WHERE ' . $where . " regions.mnemocode = '" . strtoupper($area_code) . "' ORDER BY " . $order . ' LIMIT ' . $limit;
        return $this->as_array($query);
    }

    public function get_segments($params, $area_id)
    {
        $columns = isset($params['columns']) ? ', ' . $params['columns'] . ' ' : ' ';
        $join = isset($params['join']) ? $params['join'] : '';
        $where = isset($params['where']) ? '(' . $params['where'] . ') AND ' : '';
        if (isset($_COOKIE['editor_level'])) {
            $where .= '(s.lock <= ' . $_COOKIE['editor_level'] . ' or s.lock is null) AND ';
        }
        $order = isset($params['order']) ? $params['order'] : 's.last_edit_on DESC';
        $limit = isset($params['limit']) ? $params['limit'] : 5000;
        $query = 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.last_edit_on, u.username as u_username, u.rank as u_rank, s.street_id, '
            . 'str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty, st.name as st_name' . $columns
            . 'FROM segments AS s '
            . 'LEFT JOIN streets as str ON (s.street_id = str.id) '
            . 'LEFT JOIN cities as c ON (str.city_id = c.id) '
            . 'LEFT JOIN states as st ON (c.state_id = st.id) '
            . 'LEFT JOIN users as u on(u.id = s.last_edit_by) ' . $join
            . ' WHERE ' . $where . ' area_id = ' . $area_id . ' ORDER BY ' . $order . ' LIMIT ' . $limit;
        return $this->as_array($query);
    }

    public function get_segments_count($params, $area_id)
    {
        $join = isset($params['join']) ? $params['join'] : '';
        $where = isset($params['where']) ? '(' . $params['where'] . ') AND ' : '';
        $query = 'SELECT count(*) ' . ' FROM segments AS s '
            . 'LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id) LEFT JOIN users as u on(u.id = s.last_edit_by) ' . $join
            . ' WHERE ' . $where . ' area_id = ' . $area_id;
        $list = $this->as_array($query);
        $list = reset($list);
        return reset($list);
    }
}
