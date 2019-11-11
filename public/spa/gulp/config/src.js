
var railsSrc = require('../../node_modules/jrails/gulp/config/src');

var src = {
    style: {
        vendorApp: [
            './node_modules/bootstrap/dist/css/bootstrap.css',
            './node_modules/bootstrap/dist/css/bootstrap-theme.css',
            './node_modules/toastr/build/toastr.min.css',
        ]
    },
    script: {
        vendorApp: [
            './node_modules/jquery/dist/jquery.min.js',
            './node_modules/bootstrap/dist/js/bootstrap.min.js',
            './node_modules/director/build/director.min.js',
            './node_modules/redux/dist/redux.min.js',
            './node_modules/vue/dist/vue.min.js',
            './node_modules/toastr/build/toastr.min.js',
            './node_modules/jquery-ui/jquery-ui.min.js',
        ],
        railsApp: [
            './node_modules/jrails/ui/*.js',
            './node_modules/jrails/notify/*.js',
            './node_modules/jrails/notify/driver/*.js',
            './node_modules/jrails/domain/*.js',
            './node_modules/jrails/rest/*.js',
            './node_modules/jrails/legalbet/*.js',
            './node_modules/jrails/widget/*.js',
            './node_modules/jrails/spa/*.js',
            './node_modules/jrails/vue/*.js',
            './node_modules/jrails/bootstrap/**/*.js',
        ],
        app: [
            './src/module/user/store/*.js',
            './src/module/user/service/*.js',
            './src/module/user/lang/ru/*.js',

            './src/module/contact/store/*.js',
            './src/module/contact/service/*.js',

            //'./src/module/notify/service/*.js',

            './src/module/person/service/*.js',
            //'./src/module/person/controller/*.js',
            './src/module/person/lang/*.js',

            './src/module/**/config/*.js',
            './src/app/controller/*.js',
            './src/app/view/*.js',

            './src/app/bootstrap.js',
            './src/app/run.js',
        ]
    }
};

src.script.vendor = railsSrc.script.vendorRequired.concat(src.script.vendorApp);
src.script.rails = railsSrc.script.railsRequired.concat(src.script.railsApp);
src.script.all = src.script.vendor.concat(src.script.rails).concat(src.script.app);

src.style.all = src.style.vendorApp;

module.exports = src;
