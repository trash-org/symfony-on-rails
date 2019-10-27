space('bundle.module.todo.controller.updateController', function() {

    var data = {
        entity: {},
    };

    return {

        data: data,
        methods: {
            save: function (event) {
                bundle.module.todo.store.contactStore.update(bundle.module.todo.controller.updateController.data.entity);
                var uri = 'todo/view/' + bundle.module.todo.controller.updateController.data.entity.id;
                bundle.spa.router.go(uri);
            }
        },

        depends: [
            'bundle.module.todo.store.contactStore',
        ],

        run: function () {
            var request = bundle.vue.loader.request;
            var entity = bundle.module.todo.store.contactStore.oneById(request.query.id);
            bundle.module.todo.controller.updateController.data.entity = _.clone(entity);
        },

    };

});