/*global $*/
/*jslint white: true, vars: true*/

(function () {
	"use strict";
	$(function () {
		$('.panel-collapse').each(function(){
			var $this = $(this);
			var id = $this.attr('id');
			var list = [];
			$this.find('.list tr:first td').each(function(){
				list.push($(this).attr('class'));
			});
			new List(id, {valueNames: list});
		})
	});
}());