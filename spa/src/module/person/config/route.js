define([
    'jrails/spa/router',
    'jrails/spa/controllerFactory',
    'module/person/vm/viewVm',
    'module/person/vm/updateVm',
], function(
    router,
    controllerFactory,
    viewVm,
    updateVm
) {

    router.addRoute('/person/view', function () {
        controllerFactory.createByClass(viewVm);
    });

    router.addRoute('/person/update', function () {
        controllerFactory.createByClass(updateVm);
    });

});
