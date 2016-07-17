<?php
	return [
		'c_road_type' => function($segment){
			return Waze\RoadsHelper::getInstance()->type($segment['roadtype']);
		},
		'c_link' => function($segment) {
			$url = isset($_COOKIE['wme_url']) ? $_COOKIE['wme_url'] : 'https://www.waze.com/';
			return '<a href="' . $url . 'editor/?zoom=5&lat=' . $segment['latitude'] . '&lon=' . $segment['longitude']
			. '&segments='. $segment['id'] . '" target="WME">' . $segment['id'] . '</a>';
		},
		'c_lock' => function($segment) {
			return $segment['lock'] ? : 'auto';

		},
		'c_title' => function($segment) {
			return Waze\RoadsHelper::getInstance()->name($segment);
		},
		'c_editor' => function($segment) {
			return $segment['u_username'] . ' (' . $segment['u_rank'] . ')';
		},
	];
