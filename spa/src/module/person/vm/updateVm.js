define([
    'jrails/kernel/container',
    'module/person/service/personService',
    'jrails/notify/notifyService',
    'jrails/event/eventService',
    'jrails/spa/router',
    'module/person/lang/ru/info',

    //

    'text!module/person/template/update.html',
], function(
    container,
    personService,
    notifyService,
    eventService,
    spaRouter,
    personLang

    //


) {

    var data = {
        errors: {},
        entity: {},
    };

    return {

        el: '#app-person-update',
        data: data,
        templateFile: 'module/person/template/update.html',
        methods: {
            save: function (event) {
                var promise = personService.update(data.entity);
                promise.then(function (data) {
                    //container.event.trigger('person.info.update', data);
                    spaRouter.go('person/view');
                }).catch(function (err) {
                    if(err.status === 422) {
                        var errors = {};
                        for(var k in err.responseJSON) {
                            var fieldName = err.responseJSON[k].field;
                            var fieldMessage = err.responseJSON[k].message;
                            errors[fieldName] = fieldMessage;
                        }
                        //console.log(errors);
                        data.errors = errors;

                        //console.log(bundle.module.user.controller.authController.data.errors);
                    }
                });
            }
        },
        access: function () {
            return {
                auth: '@',
            };
        },
        created: function () {
            eventService.registerHandler('person.info.update', function (entity) {
                notifyService.success(personLang.infoUpdatedMessage);
            })
        },
        run: function () {
            personService.oneSelf().then(function (entity) {
                data.entity = entity;
            });
        },
    };

});
