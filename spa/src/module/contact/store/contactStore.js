space('bundle.module.contact.store.contactStore', function() {

    var actionEnum = {
        update: 'update',
    };

    var userReducer = function(state, action) {
        if (state === undefined) {
            state = {};
        }
        if (action.type === actionEnum.update) {
            state = _.clone(action.data);
            container.event.trigger('bundle.module.contact.store.contactStore.update', state);
        }
        return state;
    };

    var store = Redux.createStore(userReducer);

    return {
        update: function (contactEntity) {
            store.dispatch({
                type: actionEnum.update,
                data: contactEntity,
            });
        },
        one: function () {
            return store.getState();
        },
    };

});
