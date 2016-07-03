<?php
return [
	'disconnected' => [
		'title' => 'Сегменты не подсоединены <small>или нет разрешенных поворотов</small>',
		'sql' => 'SELECT s.dc_density, s.latitude, s.longitude, s.id, s.roadtype, s.street_id FROM segments AS s
		WHERE connected = false and roadtype NOT IN (18,10,5,19,16) and area_id = :area_id',
		'fields' => [
			'Вес' => 'dc_density',
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
		],
	],
	'lock' => [
		'title' => 'Недостаточный уроверь блокировки',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.lock FROM segments AS s
		WHERE ((roadtype=2 and coalesce(lock,0) < 2) or (roadtype=7 and coalesce(lock,0) < 3) or (roadtype in (3,4,6) and coalesce(lock,0) < 4)) and area_id = :area_id LIMIT 1000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Уровень блокировки' => 'lock',
		],
	],
	'check_speed' => [
		'title' => 'Скорость не проверена',
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection FROM segments AS s
		WHERE ((fwddirection and fwdmaxspeedunverified) or (revdirection and revmaxspeedunverified)) and area_id = :area_id LIMIT 1000',
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
		'sql' => 'SELECT s.latitude, s.longitude, s.id, s.roadtype, s.street_id, s.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection FROM segments AS s
		WHERE (roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed is null) or (revdirection and revmaxspeed is null))) and area_id = :area_id ORDER BY s.city_id LIMIT 1000',
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
		],
	]
];