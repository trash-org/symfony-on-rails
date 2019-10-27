space('bundle.module.contact.controller.oneController', function() {

    return {

        depends: [
            'bundle.module.contact.store.contactStore',
        ],

        run: function (request) {

        },

        onLoadDepends: function (request) {
            bundle.spa.helper.registerEventHandlers(request);
            var contactEntity = this.forgeEntityFromId(request.query.id);
            this.setValue(contactEntity);
            this.dumpStateToConsole();
        },

        forgeEntityFromId: function (id) {
            var contactEntity = {};
            contactEntity.id = id;
            contactEntity.title = 'title ' + id;
            contactEntity.content = 'content ' + id;
            contactEntity.deleteAction = '#contact/delete/' + id;
            contactEntity.updateAction = '#contact/update/' + id;
            return contactEntity;
        },

        setValue: function (contactEntity) {
            bundle.module.contact.store.contactStore.update(contactEntity);
        },

        dumpStateToConsole: function () {
            var contactEntity = bundle.module.contact.store.contactStore.one();
            console.log('STATE:', contactEntity);
        },

    };

});