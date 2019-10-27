space(function() {

    var router = use('bundle.spa.router');

    router.addRoute('/bskit', function () {
        bundle.vue.loader.run({
            controller: 'bskit',
            action: 'all',
        });
    });

    router.addRoute('/bskit/:id', function (id) {
        bundle.vue.loader.run({
            controller: 'bskit',
            action: id,
            query: {
                id: id,
            },
        });
    });

});