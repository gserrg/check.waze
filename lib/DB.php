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
}