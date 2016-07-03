<?php
return [
	'disconnected' => [
		'title' => 'Сегменты не подсоединены <small>или нет разрешенных поворотов</small>',
		'sql' => 'SELECT s.dc_density, s.latitude, s.longitude, s.id, s.roadtype, s.street_id
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			WHERE connected = false and roadtype NOT IN (18,10,5,19,16) and area_id = :area_id',
		'fields' => [
			'Вес' => 'dc_density',
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
		],
	],
	'lock' => [
		'title' => 'Недостаточный уроверь блокировки',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.lock
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			WHERE ((roadtype=2 and coalesce(lock,0) < 2) or (roadtype=7 and coalesce(lock,0) < 3) or (roadtype in (3,4,6) and coalesce(lock,0) < 4)) and area_id = :area_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Уровень блокировки' => 'lock',
		],
	],
	'check_speed' => [
		'title' => 'Скорость не проверена',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			WHERE ((fwddirection and fwdmaxspeedunverified) or (revdirection and revmaxspeedunverified)) and area_id = :area_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Скорость от A к B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'Скорость от B к A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
		],
	],
	'no_speed' => [
		'title' => 'Важные дороги без скоростей',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			WHERE (roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed is null) or (revdirection and revmaxspeed is null))) and area_id = :area_id ORDER BY s.city_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
		],
	],
	'bad_level' => [
		'title' => 'Некорректное возвышение',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.level
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			WHERE (s.level < -3 or s.level > 3) and area_id = :area_id ORDER BY s.city_id LIMIT 2000',
		'fields' => [
			'Возвышение' => 'level',
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
		],
	],
	'not_completed' => [
		'title' => 'Неподтвержденные сегменты',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.last_edit_on, u.username as u_username, u.rank as u_rank
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			LEFT JOIN users as u on(u.id = s.last_edit_by)
			WHERE (street_id is null) and area_id = :area_id ORDER BY s.city_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'no_name' => [
		'title' => 'Важные дороги без названия',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.last_edit_on, u.username as u_username, u.rank as u_rank
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			LEFT JOIN users as u on(u.id = s.last_edit_by)
			WHERE (str.isempty = TRUE and s.alt_names = FALSE AND roadtype in (3,6,7) AND roundabout = FALSE) and area_id = :area_id ORDER BY s.city_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'wrong_speed' => [
		'title' => 'Важные сегменты вне НП со скоростью 60км/ч',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection, s.last_edit_on, u.username as u_username, u.rank as u_rank
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			LEFT JOIN users as u on(u.id = s.last_edit_by)
			WHERE (roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed = 60) or (revdirection and revmaxspeed = 60)) and c.isempty = TRUE) and area_id = :area_id ORDER BY s.city_id LIMIT 2000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
			'Скорость от A к B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'Скорость от B к A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
		],
	],
	'railroad' => [
		'title' => 'Железная дорога с названием',
		'sql' => "SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.last_edit_on, u.username as u_username, u.rank as u_rank
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			LEFT JOIN users as u on(u.id = s.last_edit_by)
			WHERE (roadtype = 18 and (str.name <> '' OR c.name <> '' OR s.alt_names = TRUE)) and area_id = :area_id ORDER BY s.city_id LIMIT 2000",
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'ring' => [
		'title' => 'Кольца с названием',
		'sql' => "SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.last_edit_on, u.username as u_username, u.rank as u_rank
			,str.name as str_name, str.isempty as str_isempty, str.city_id as str_city_id, c.name as c_name, c.isempty as c_isempty FROM segments AS s
			LEFT JOIN streets as str ON (s.street_id = str.id) LEFT JOIN cities as c ON (str.city_id = c.id)
			LEFT JOIN users as u on(u.id = s.last_edit_by)
			WHERE ((str.name <> '' OR s.alt_names = TRUE) AND s.roundabout = TRUE) and area_id = :area_id ORDER BY s.city_id LIMIT 2000",
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
];