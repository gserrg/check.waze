<?php
	return [
		'c_road_type' => function($segment){
			return \Waze\RoadsHelper::instance()->type($segment['roadtype']);
		},
		'c_link' => function($segment) {
			return '<a href="https://www.waze.com/editor/?zoom=5&lat=' . $segment['latitude'] . '&lon=' . $segment['longitude']
			. '&segments='. $segment['id'] . '" target="WME">' . Waze\RoadsHelper::instance()->name($segment) . '</a>';
		},
		'c_editor' => function($segment) {
			return $segment['username'] . ' (' . $segment['rank'] . ')';
		},
	];
