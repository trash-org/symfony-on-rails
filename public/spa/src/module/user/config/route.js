define([
    'jrails/spa/router',
    'jrails/spa/controllerFactory',
    'module/user/vm/authVm',
    'module/user/vm/logoutVm'
], function(
    router,
    controllerFactory,
    authVm,
    logoutVm
) {

    router.addRoute('/user/auth', function () {
        controllerFactory.createByClass(authVm);
    });

    router.addRoute('/user/logout', function () {
        controllerFactory.createByClass(logoutVm);
    });

});