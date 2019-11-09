var gulp = require('gulp');
var build = require('./gulp/script/build');

gulp.task('build-dev', build.dev);
gulp.task('build-dist', build.dist);
gulp.task('build-one', build.one);
