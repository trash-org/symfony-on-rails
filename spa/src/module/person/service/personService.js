define(['jrails/event/eventService', 'jrails/rest/client'], function(eventService, restClient) {

    return {

        oneSelf: function () {
            var promise = restClient.get('person');
            promise.then(function (entity) {
                eventService.trigger('person.info.view', entity);
            });
            return promise;
        },

        update: function (entity) {
            var promise = restClient.put('person', {
                first_name: entity.first_name,
                middle_name: entity.middle_name,
                last_name: entity.last_name,
                birthday: entity.birthday,
                code: entity.code,
                email: entity.email,
            });
            promise.then(function (data) {
                eventService.trigger('person.info.update', data);
            });
            return promise;
        },

    };

});
