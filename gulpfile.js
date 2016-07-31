'use strict';

var gulp = require('gulp');
var livereload = require('gulp-livereload');

gulp.task('sync', function () {
	livereload();
	livereload.listen();
	gulp.watch([
			'config/**/*.php',
			'Waze/**/*.php',
			'render/**/*.phtml',
			'www/index.php',
			'www/builds/*.css',
			'www/builds/*.js'
	],function(){
		livereload.reload();
	});
});

gulp.task('default', gulp.parallel('sync'));