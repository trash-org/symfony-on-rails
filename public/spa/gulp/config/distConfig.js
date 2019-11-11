
// наследуем конфиг
var config = require('jrails/requirejs/requirejs-deps');
// указываем в какой файл собирать
config.out = "dist/assets/built.js";
config.name = "src/app/index";

config.paths['module'] = 'src/module';
config.paths['widget'] = 'src/widget';
config.paths['app'] = 'src/app';

config.include = [
    'node_modules/requirejs/require',
];

module.exports = config;