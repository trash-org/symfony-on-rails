space('bundle.module.todo.controller.oneController', function() {

    var data = {
        entity: {},
    };

    return {

        data: data,
        depends: [
            'bundle.module.todo.store.contactStore',
        ],

        run: function () {
            var request = bundle.vue.loader.request;
            var entity = bundle.module.todo.store.contactStore.oneById(request.query.id);
            bundle.module.todo.controller.oneController.data.entity = _.clone(entity);
        },

    };

});