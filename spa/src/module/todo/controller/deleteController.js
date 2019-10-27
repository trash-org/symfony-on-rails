space('bundle.module.todo.controller.deleteController', function() {

    var data = {
        entity: {},
    };

    return {

        data: data,
        methods: {
            del: function (event) {
                bundle.module.todo.store.contactStore.deleteById(bundle.module.todo.controller.deleteController.data.entity.id);
                bundle.spa.router.go('todo');
            }
        },

        depends: [
            'bundle.module.todo.store.contactStore',
        ],

        run: function () {
            var request = bundle.vue.loader.request;
            var entity = bundle.module.todo.store.contactStore.oneById(request.query.id);
            bundle.module.todo.controller.deleteController.data.entity = _.clone(entity);
        },

    };

});