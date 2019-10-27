space('bundle.module.person.service.personService', function() {

    return {

        oneSelf: function () {
            var promise = container.restClient.get('person');
            promise.then(function (entity) {
                container.event.trigger('person.info.view', entity);
            });
            return promise;
        },

        update: function (entity) {
            var promise = container.restClient.put('person', {
                first_name: entity.first_name,
                middle_name: entity.middle_name,
                last_name: entity.last_name,
                birthday: entity.birthday,
                code: entity.code,
                email: entity.email,
            });
            promise.then(function (data) {
                container.event.trigger('person.info.update', data);
            });
            return promise;
        },

    };

});
