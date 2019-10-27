space(function() {

    var router = use('bundle.spa.router');

    router.addRoute('/todo', function () {
        bundle.vue.loader.run({
            controller: 'todo',
            action: 'all',
        });
    });

    router.addRoute('/todo/create', function (id) {
        bundle.vue.loader.run({
            controller: 'todo',
            action: 'create',
        });
    });

    router.addRoute('/todo/view/:id', function (id) {
        bundle.vue.loader.run({
            controller: 'todo',
            action: 'one',
            query: {
                id: id,
            },
        });
    });

    router.addRoute('/todo/update/:id', function (id) {
        bundle.vue.loader.run({
            controller: 'todo',
            action: 'update',
            query: {
                id: id,
            },
        });
    });

    router.addRoute('/todo/delete/:id', function (id) {
        bundle.vue.loader.run({
            controller: 'todo',
            action: 'delete',
            query: {
                id: id,
            },
        });
    });

});