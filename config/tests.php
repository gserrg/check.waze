<?php
return [
	'disconnected' => [
		'title' => 'Сегменты не подсоединены <small>или нет разрешенных поворотов</small>',
		'sql' => [
			'columns' => 's.street_id, s.dc_density',
			'where' => 'connected = false and roadtype NOT IN (18,10,5,19,16)',
		],
		'fields' => [
			'Вес' => 'dc_density',
			'Расположение сегмента' => 'c_link',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
			'Тип дороги' => 'c_road_type',
		],
	],
	'lock' => [
		'title' => 'Недостаточный уроверь блокировки',
		'sql' => [
			'columns' => 's.lock',
			'where' => '(roadtype=2 and coalesce(lock,0) < 2) or (roadtype=7 and coalesce(lock,0) < 3) or (roadtype in (3,4,6) and coalesce(lock,0) < 4)',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
			'Уровень блокировки' => 'lock',
		],
	],
	'check_speed' => [
		'title' => 'Скорость не проверена',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => '(fwddirection and fwdmaxspeedunverified) or (revdirection and revmaxspeedunverified)',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
			'км/ч A->B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'км/ч B->A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
		],
	],
	'no_speed' => [
		'title' => 'Важные дороги без скоростей',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => 'roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed is null) or (revdirection and revmaxspeed is null))',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'bad_level' => [
		'title' => 'Некорректное возвышение',
		'sql' => [
			'columns' => 's.level',
			'where' => 's.level < -3 or s.level > 3',
		],
		'fields' => [
			'Возвышение' => 'level',
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'not_completed' => [
		'title' => 'Неподтвержденные сегменты',
		'sql' => [
			'where' => 'street_id is null',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'no_name' => [
		'title' => 'Важные дороги без названия',
		'sql' => [
			'where' => 'str.isempty = TRUE and s.alt_names = FALSE AND roadtype in (3,6,7) AND roundabout = FALSE',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'wrong_speed' => [
		'title' => 'Важные сегменты вне НП со скоростью 60км/ч',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => 'roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed = 60) or (revdirection and revmaxspeed = 60)) and c.isempty = TRUE',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
			'км/ч A->B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'км/ч B->A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
		],
	],
	'railroad' => [
		'title' => 'Железная дорога с названием',
		'sql' => [
			'where' => 'roadtype = 18 and (str.name <> \'\' OR c.name <> \'\' OR s.alt_names = TRUE)',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'ring' => [
		'title' => 'Кольца с названием',
		'sql' => [
			'where' => '(str.name <> \'\' OR s.alt_names = TRUE) AND s.roundabout = TRUE',
		],
		'fields' => [
			'Расположение сегмента' => 'c_link',
			'Тип дороги' => 'c_road_type',
			'Последнее обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
];