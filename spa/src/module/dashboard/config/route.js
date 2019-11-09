define([
    'jrails/spa/router',
    'jrails/spa/controllerFactory',
    'module/dashboard/vm/mainVm',
], function(
    router,
    controllerFactory,
    mainVm
) {

    router.addRoute('/', function () {
        controllerFactory.createByClass(mainVm);
    });

});