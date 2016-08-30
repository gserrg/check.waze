/*global $*/
/*jslint white: true, vars: true*/

ymaps.ready(init);
var myMap;

function init () {
	myMap = new ymaps.Map('map', {
		center: [55.84, 45.18],
		zoom: 4
	});
	myMap.controls.add('mapTools');
	myMap.controls.add('zoomControl');
	var polygons = coordinates();
	var i;
	for (i in polygons) {
		if (polygons.hasOwnProperty(i)) {
			var poly = new ymaps.Polygon(polygons[i].poly, {
				hintContent: polygons[i].name
			}, {
				fillColor: polygons[i].color,
				fillOpacity: 0.6
			});
			poly.href = 'test_area/' + polygons[i].id + '/';
			poly.events.add('click', function (e) {
				document.location.href = e.get('target').href;
			});
			myMap.geoObjects.add(poly);
		}
	}
}