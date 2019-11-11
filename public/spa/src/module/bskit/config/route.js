define([
    'jrails/spa/router',
    'jrails/spa/controllerFactory',
    'module/bskit/vm/allVm',
    'module/bskit/vm/oneVm',
], function(
    router,
    controllerFactory,
    allVm,
    oneVm
) {

    router.addRoute('/bskit', function () {
        controllerFactory.createByClass(allVm);
    });

    router.addRoute('/bskit/:id', function (id) {
        controllerFactory.createByClass(oneVm, {id: id});
    });

});