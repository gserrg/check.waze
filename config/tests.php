<?php
return [
	'disconnected' => [
		'title' => '<span title="Или нет разрешенных поворотов">Сегменты не подсоединены</span>',
		'sql' => [
			'columns' => 's.street_id',
			'where' => 'connected = false and roadtype NOT IN (18,10,5,19,16)',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'lock' => [
		'title' => 'Недостаточный уроверь блокировки',
		'sql' => [
			'columns' => 's.lock',
			'where' => '(roadtype in (2,3,4,6,7) and lock is null) or (roadtype=2 and lock < 2) or (roadtype=7 and lock < 3) or (roadtype in (3,4,6) and lock < 4)',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Уровень блокировки' => 'c_lock',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'wrong_speed' => [
		'title' => 'Важные сегменты вне НП с 60км/ч',
		'sql' => [
			'columns' => 's.fwdmaxspeed, s.revmaxspeed, s.fwddirection, s.revdirection',
			'where' => 'roadtype in (2,3,4,6,7) and ((fwddirection and fwdmaxspeed = 60) or (revdirection and revmaxspeed = 60)) and c.isempty = TRUE',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
	'railroad' => [
		'title' => 'Железная дорога с названием',
		'sql' => [
			'where' => 'roadtype = 18 and (str.name <> \'\' OR c.name <> \'\' OR s.alt_names = TRUE)',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
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
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'short' => [
		'title' => 'Короткие сегменты',
		'sql' => [
			'columns' => 's.length',
			'where' => 'roadtype in (1,2,3,4,6,7) and length < 6 AND s.roundabout = FALSE AND fwddirection',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Длина' => 'length',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'unpaved' => [
		'title' => 'Важные дороги без покрытия',
		'sql' => [
			'where' => 'unpaved = TRUE AND roadtype in (3,6,7,2,4)',
		],
		'fields' => [
			'' => 'c_link',
			'Город' => 'c_title_city',
			'Улица' => 'c_title_street',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
	'unpaved_no_city' => [
		'title' => 'Дороги вне НП без покрытия',
		'sql' => [
			'where' => 'unpaved = TRUE and c.isempty = TRUE',
		],
		'fields' => [
			'' => 'c_link',
			'Улица' => 'c_title_street',
			'Тип дороги' => 'c_road_type',
			'Обновление' => 'last_edit_on',
			'Редактор' => 'c_editor',
		],
	],
];