space('bundle.module.person.controller.updateController', function() {

    var data = {
        errors: {},
        entity: {},
    };

    return {

        data: data,
        depends: [
            'bundle.module.person.service.personService',
            '/src/module/person/lang/ru/info.js',
        ],
        methods: {
            save: function (event) {
                var promise = bundle.module.person.service.personService.update(data.entity);
                promise.then(function (data) {
                    //container.event.trigger('person.info.update', data);
                    bundle.spa.router.go('person/view');
                }).catch(function (err) {
                    if(err.status === 422) {
                        var errors = {};
                        for(var k in err.responseJSON) {
                            var fieldName = err.responseJSON[k].field;
                            var fieldMessage = err.responseJSON[k].message;
                            errors[fieldName] = fieldMessage;
                        }
                        console.log(errors);
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
        run: function () {
            bundle.module.person.service.personService.oneSelf().then(function (entity) {
                data.entity = entity;
            });
        },
        created: function () {
            container.event.registerHandler('person.info.update', function (entity) {
                container.notify.success(lang.person.info.infoUpdatedMessage);
            })
        },
    };

});