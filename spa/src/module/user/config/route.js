space(function() {

    var router = use('bundle.spa.router');

    router.addRoute('/user/auth', function () {
        bundle.vue.loader.run({
            controller: 'user',
            action: 'auth',
        });
    });

    router.addRoute('/user/logout', function () {
        bundle.vue.loader.run({
            controller: 'user',
            action: 'logout',
        });
    });

});