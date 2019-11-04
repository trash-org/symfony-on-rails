
// наследуем конфиг
var config = require('jrails/requirejs/requirejs-deps');
// указываем в какой файл собирать
config.out = "src/assets/vendor.js";
config.include = [
    'node_modules/requirejs/require',
    'DirectorRouter',
    'lodash',
    'jquery',
    'twitterBootstrap',
    'vue',
    'text',
    'jqueryUi',
    'jrails/spa/router',
    'jrails/helper/php',
    'jrails/event/eventService',
    'jrails/helper/class',
    'jrails/helper/localStorage',
    'jrails/domain/baseLocalStorage',
    'jrails/kernel/container',
    'jrails/rest/client',
    'jrails/notify/notifyTypeEnum',
    'jrails/notify/notifyService',
    'jrails/notify/driver/toastrDriver',
    'jrails/bootstrap/modal/modalService',
    'jrails/vue/vm',
    'jrails/spa/layer',
    'jrails/spa/query',
    'jrails/spa/controllerFactory',
];

module.exports = config;
