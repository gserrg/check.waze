'use strict';

var gulp = require('gulp');
var livereload = require('gulp-livereload');

gulp.task('sync', function () {
	livereload();
	livereload.listen();
	gulp.watch([
			'**/*.php',
			'render/*.phtml',
			'www/build/*.css',
			'www/build/*.js'
	],function(){
		livereload.reload();
	});
});

gulp.task('default', gulp.parallel('sync'));