/*global $*/
/*jslint white: true, vars: true*/

ymaps.ready(init);
var myMap;

function init () {
	myMap = new ymaps.Map('map', {
		center: [55.84, 45.18],
		zoom: 4
	});
	var polygons = polygon();
	var i;
	for (i in polygons) {
		myMap.geoObjects.add(new ymaps.Polygon(polygons[i], {}, {fillColor: i}));
	}
}
