/*global $*/
/*jslint white: true, vars: true*/

(function () {
	"use strict";
	var cookieGet = function (n) {
		var c = ' ' + document.cookie;
		var s = ' ' + n + '=';
		var ss = null;
		var o = 0;
		var e = 0;
		if (c.length > 0) {
			o = c.indexOf(s);
			if (o != -1) {
				o += s.length;
				e = c.indexOf(';', o);
				if (e == -1) {
					e = c.length;
				}
				ss = decodeURI(c.substring(o, e));
			}
		}
		return (ss);
	};
	var cookieSet = function (n, v, e, p, d, s) {
		if (e) {
			var t = new Date(new Date().getTime() + (e * 1000));
			e = t.toUTCString()
		}
		document.cookie = n + '=' + encodeURI(v) + ((e) ? '; expires=' + e : '') + ((p) ? '; path=' + p : '') + ((d) ? '; domain=' + d : '') + ((s) ? '; secure' : '');
	};
	$(function () {
		var url = cookieGet('wme_url');
		if (url != undefined) {
			$('.form-control[name="wme_url"]').val(url);
		}
		var level = cookieGet('editor_level');
		if (level != undefined) {
			$('.form-control[name="editor_level"]').val(level);
		}
		$('.settings-form').find('button[type="submit"]').click(function(){
			var days = 60;
			cookieSet('wme_url', $('.form-control[name="wme_url"]').val(), 86400 * days, '/');
			cookieSet('editor_level', $('.form-control[name="editor_level"]').val(), 86400 * days , '/');
			alert('Сохранено!')
			return false;
		});
	});
}());