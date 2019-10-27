space(function() {

    var router = use('bundle.spa.router');

    router.addRoute('/person/view', function () {
        bundle.vue.loader.run({
            controller: 'person',
            action: 'view',
        });
    });

    router.addRoute('/person/update', function () {
        bundle.vue.loader.run({
            controller: 'person',
            action: 'update',
        });
    });

});