space('bundle.module.todo.controller.allController', function() {

    var data = {
        collection: {},
    };

    return {

        data: data,
        depends: [
            'bundle.module.todo.store.contactStore',
        ],

        created: function (request) {
            data.collection = bundle.module.todo.store.contactStore.all();
        },

    };

});
