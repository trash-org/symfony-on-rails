space(function() {

    var router = use('bundle.spa.router');
    var module = use('bundle.spa.module');

    router.addRoute('/contact', function () {
        module.run({
            controller: 'contact',
            action: 'all',
        });
    });

    router.addRoute('/contact/view/:id', function (id) {
        module.run({
            controller: 'contact',
            action: 'one',
            query: {
                id: id,
            },
        });
    });

    router.addRoute('/contact/update/:id', function (id) {
        module.run({
            controller: 'contact',
            action: 'update',
            query: {
                id: id,
            },
        });
    });

    router.addRoute('/contact/delete/:id', function (id) {
        module.run({
            controller: 'contact',
            action: 'delete',
            query: {
                id: id,
            },
        });
    });

});