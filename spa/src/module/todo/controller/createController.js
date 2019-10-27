space('bundle.module.todo.controller.createController', function() {

    var data = {
        entity: {
            title: '',
            content: '',
        },
    };

    return {

        data: data,
        methods: {
            save: function (event) {
                var entity = _.clone(bundle.module.todo.controller.createController.data.entity);
                bundle.module.todo.store.contactStore.create(entity);
                bundle.module.todo.controller.createController.data.entity = {};
                bundle.spa.router.go('todo');
            }
        },

        depends: [
            'bundle.module.todo.store.contactStore',
        ],

    };

});