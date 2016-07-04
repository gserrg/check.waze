<?php
return [
	'disconnected' => [
		'title' => 'Сегменты не подсоединены <small>или нет разрешенных поворотов</small>',
		'sql' => [
			'columns' => 's.street_id, s.dc_density',
			'where' => 'connected = false and roadtype NOT IN (18,10,5,19,16)',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Вес' => 'dc_density',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'lock' => [
		'title' => 'Недостаточный уроверь блокировки',
		'sql' => [
			'columns' => 's.lock',
			'where' => '(roadtype=2 and coalesce(lock,0) < 2) or (roadtype=7 and coalesce(lock,0) < 3) or (roadtype in (3,4,6) and coalesce(lock,0) < 4)',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Уровень блокировки' => 'lock',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'check_speed' => [
		'title' => 'Скорость не проверена',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => '(fwddirection and fwdmaxspeedunverified) or (revdirection and revmaxspeedunverified)',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'A->B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'B->A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'no_speed' => [
		'title' => 'Важные дороги без скоростей',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => 'roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed is null) or (revdirection and revmaxspeed is null))',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
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
			'' => 'c_link',
			'Название' => 'c_title',
			'Возвышение' => 'level',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'not_completed' => [
		'title' => 'Неподтвержденные сегменты',
		'sql' => [
			'where' => 'street_id is null',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'no_name' => [
		'title' => 'Важные дороги без названия',
		'sql' => [
			'where' => 'str.isempty = TRUE and s.alt_names = FALSE AND roadtype in (3,6,7) AND roundabout = FALSE',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
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
			'' => 'c_link',
			'Название' => 'c_title',
			'Тип дороги' => 'c_road_type',
			'A->B' => function($segment){
				return $segment['fwddirection'] != false ? $segment['fwdmaxspeed'] : '-';
			},
			'B->A' => function($segment){
				return $segment['revdirection'] != false ? $segment['revmaxspeed'] : '-';
			},
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'railroad' => [
		'title' => 'Железная дорога с названием',
		'sql' => [
			'where' => 'roadtype = 18 and (str.name <> \'\' OR c.name <> \'\' OR s.alt_names = TRUE)',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'ring' => [
		'title' => 'Кольца с названием',
		'sql' => [
			'where' => '(str.name <> \'\' OR s.alt_names = TRUE) AND s.roundabout = TRUE',
		],
		'fields' => [
			'' => 'c_link',
			'Название' => 'c_title',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
];