<?php
	return [
		'c_road_type' => function($segment){
			return Waze\RoadsHelper::getInstance()->type($segment['roadtype']);
		},
		'c_link' => function($segment) {
			return '<a href="https://www.waze.com/editor/?zoom=5&lat=' . $segment['latitude'] . '&lon=' . $segment['longitude']
			. '&segments='. $segment['id'] . '" target="WME">' . $segment['id'] . '</a>';
		},
		'c_title' => function($segment) {
			return Waze\RoadsHelper::getInstance()->name($segment);
		},
		'c_editor' => function($segment) {
			return $segment['u_username'] . ' (' . $segment['u_rank'] . ')';
		},
	];
