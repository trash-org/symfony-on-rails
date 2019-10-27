space(function() {

    var router = use('bundle.spa.router');

    router.addRoute('/', function () {
        bundle.spa.module.run({
            controller: 'main',
        });
    });

});