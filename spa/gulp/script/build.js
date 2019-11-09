var gulp = require('gulp');
var src = require('../config/src');
var builderTypeHelper = require('../../node_modules/jrails/gulp/script/builderTypeHelper');
var rjs = require('gulp-requirejs');
var del = require('del');
var replace = require('gulp-replace');
var fs = require("fs");

module.exports = {

    one: function () {
        fs.readFile("./dist/assets/built.js", "utf-8", function(err, code) {
            gulp.src(['./src/root/index.html'])
                .pipe(replace('<!--SCRIPT_PLACEHOLDER-->', '<script>\n' + code + '\n</script>'))
                .pipe(gulp.dest('./dist/one'));
            fs.readFile("./dist/assets/built.css", "utf-8", function(err, code) {
                gulp.src(['./dist/one/index.html'])
                    .pipe(replace('<!--STYLE_PLACEHOLDER-->', '<style>\n' + code + '\n</style>'))
                    .pipe(gulp.dest('./dist/one'));
            });
        });
    },

    dist: function () {
        var dir = './dist/assets';
        // удаление сбоорки (нужно для пересборки)
        var promise = del(dir);
        promise.then(function () {
            var config = require('../config/distConfig');
            rjs(config).pipe(gulp.dest('.'));
            // сборка стилей для боя
            builderTypeHelper.buildStyle(src.style.all, dir, 'built.css', true);
            // копируем нужные файлы
            builderTypeHelper.copy(['./src/root/*'], 'dist');
        });
    },

    dev: function () {
        var dir = './src/assets';
        // удаление сбоорки (нужно для пересборки)
        var promise = del(dir);
        promise.then(function () {
            var config = require('../config/devConfig');
            rjs(config).pipe(gulp.dest('.'));
            // сборка стилей для разработки
            builderTypeHelper.buildStyle(src.style.all, dir, 'vendor.css', true);
        });
    },

};
