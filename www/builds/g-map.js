/*global google,*/
/*jslint white: true, vars: true*/
var map;
var boxes = [];
var lastBox;
var clickCoordinates = {lat: 55.7625376, lng: 37.6223241};
document.addEventListener("DOMContentLoaded", initControls);
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 55.33137, lng: 37.63916},
		zoom: 4
	});
	renderBoxes();
}
function initControls() {
	document.getElementById('render').onclick = function(){
		renderBoxes();
	};
	document.getElementById('add').onclick = function(){
		if (lastBox) {
			var bound = myBound(lastBox);
			var newBound = {
				west: 0,
				north: 0,
				east: 0,
				south: 0
			};
			if (lastBox.fixFixed.hasOwnProperty('west')) {
				newBound['east'] = bound['west'];
			}
			if (lastBox.fixFixed.hasOwnProperty('north')) {
				newBound['south'] = bound['north'];
			}
			if (lastBox.fixFixed.hasOwnProperty('east')) {
				newBound['west'] = bound['east'];
			}
			if (lastBox.fixFixed.hasOwnProperty('south')) {
				newBound['north'] = bound['south'];
			}
			if (newBound['west'] == 0) {
				newBound['west'] = newBound['east'] != 0 ? newBound['east'] - 2 : bound['east'];
			}
			if (newBound['north'] == 0) {
				newBound['north'] = newBound['south'] != 0 ? newBound['south'] - 1 : bound['south'];
			}
			if (newBound['east'] == 0) {
				newBound['east'] = newBound['west'] != 0 ? newBound['west'] + 2 : bound['west'];
			}
			if (newBound['south'] == 0) {
				newBound['south'] = newBound['north'] != 0 ? newBound['north'] + 1 : bound['north'];
			}
			console.log(newBound);
			addBox([newBound.west, newBound.north, newBound.east, newBound.south ], fixedToArray(lastBox));
		} else {
			addBox([clickCoordinates.lng, clickCoordinates.lat - 0.3, clickCoordinates.lng + 2, clickCoordinates.lat]);
		}
	};
	document.getElementById('save').onclick = function(){
		saveBoxes();
	};
	document.getElementById('fixed').onclick = function(){
		if (!lastBox) {
			alert('Кликов небыло');
			return;
		}
		updateFixes('south');
		updateFixes('north');
		updateFixes('west');
		updateFixes('east');
	};
}
function updateFixes(type) {
	var status = document.getElementById(type).checked;
	var bound = myBound(lastBox);
	if (status && !lastBox.fixFixed.hasOwnProperty(type)) {
		lastBox.fixFixed[type] = bound[type];
	}
	if (!status && lastBox.fixFixed.hasOwnProperty(type)) {
		delete lastBox.fixFixed[type];
	}
}
function renderBoxes() {
	var coordinates = document.getElementById('list').value.split('\n');
	var i;
	var points;
	for (i in coordinates) {
		if (coordinates.hasOwnProperty(i)) {
			points = coordinates[i].split(',');
			if (points.length == 4) {
				addBox(points);
			}
		}
	}
}
function myBound(object) {
	var bounds = object.getBounds();
	var sw = bounds.getSouthWest();
	var ne = bounds.getNorthEast();
	return {
		west: sw.lng(),
		north: ne.lat(),
		east: ne.lng(),
		south: sw.lat()
	}
}
function fixedToArray(object) {
	var fixes = [];
	var i;
	for (i in object.fixFixed) {
		if (object.fixFixed.hasOwnProperty(i)) {
			fixes.push([i]);
		}
	}
	return fixes;
}
//20.8 55.3 21.07 55.21
//долгота широта
function addBox(points, fixed) {
	fixed = fixed || [];
	var bound = {
		west: parseFloat(points[0]), //запад
		north: parseFloat(points[1]), //севек
		east: parseFloat(points[2]), //восток
		south: parseFloat(points[3]) //юг
	};
	var rectangle = new google.maps.Rectangle({
		bounds: bound,
		editable: true
	});
	var i;
	var fixBound = {};
	for (i in fixed) {
		if (fixed.hasOwnProperty(i)) {
			fixBound[fixed[i]] = bound[fixed[i]];
		}
	}
	rectangle.fixFixed = fixBound;
	rectangle.addListener('click', function(){
		lastBox = this;
		document.getElementById('south').checked = this.fixFixed.hasOwnProperty('south');
		document.getElementById('north').checked = this.fixFixed.hasOwnProperty('north');
		document.getElementById('west').checked = this.fixFixed.hasOwnProperty('west');
		document.getElementById('east').checked = this.fixFixed.hasOwnProperty('east');
	});
	rectangle.addListener('bounds_changed', function(){
		lastBox = this;
		var bound = myBound(this);
		function check(object, property) {
			if(object.fixFixed.hasOwnProperty(property)) {
				bound[property] = object.fixFixed[property];
				return true;
			}
			return false;
		}
		var fix = check(this, 'south');
		fix = check(this, 'north') || fix;
		fix = check(this, 'west') || fix;
		fix = check(this, 'east') || fix;
		if (fix) {
			lastBox = addBox([bound['west'],bound['north'],bound['east'],bound['south']], fixedToArray(this));
			this.setMap(null);
		}
	});
	rectangle.setMap(map);
	map.setCenter(rectangle.getBounds().getCenter());
	boxes.push(rectangle);
	return rectangle;
}
function saveBoxes() {
	var i;
	var str = '';
	for (i in boxes) {
		if (boxes.hasOwnProperty(i) && boxes[i].getMap()) {
			str += boxes[i].getBounds().toString() + '\n';
		}
	}
	document.getElementById('list').value = str;
}