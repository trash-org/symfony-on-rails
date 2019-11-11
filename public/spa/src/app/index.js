
requirejs.config({
    baseUrl: 'src',
    urlArgs: "bust=" + (new Date()).getTime(), // отмена кэширования скриптов браузером
    paths: {
        module: '../module',
        widget: '../widget',
        app: '../app'
    },
    shim: {
        'DirectorRouter': {
            exports: 'Router'
        },
        'lodash': {
            exports: '_'
        },
        'jquery': {
            exports: '$'
        },
        "jqueryUi": {
            exports: "$",
            deps: ['jquery']
        },
        "twitterBootstrap": {
            deps: ["jquery"]
        },
        'toastr': {
            exports: 'toastr',
            deps: ["jquery"]
        },
        'vue': {
            exports: 'Vue'
        }
    }
});

requirejs(['app/bootstrap'], function (bootstrap) {
    bootstrap.run();
});
