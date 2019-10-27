space('bundle.module.user.controller.authController', function() {

    var data = {
        entity: {},
        errors: {
            login: '',
            password: '',
        },
    };

    return {

        data: data,
        depends: [
            //'bundle.module.user.store.authStore',
        ],
        methods: {
            auth: function (event) {
                var promise = container.authService.auth(bundle.module.user.controller.authController.data.entity);
                promise.then(function (identity) {
                    bundle.module.user.controller.authController.data.entity = {};
                    bundle.spa.router.go();
                    console.log(identity);
                    container.notify.success(lang.user.auth.successAuthorizationMessage);
                }).catch(function (err) {
                    if(err.status === 422) {
                        bundle.module.user.controller.authController.data.errors = {};
                        for(var k in err.responseJSON) {
                            var fieldName = err.responseJSON[k].field;
                            var fieldMessage = err.responseJSON[k].message;
                            bundle.module.user.controller.authController.data.errors[fieldName] = fieldMessage;
                            //console.log([fieldName, fieldMessage]);
                        }
                        console.log(bundle.module.user.controller.authController.data.errors);
                    }
                });
            },
        },
        access: function () {
            return {
                auth: '?',
            };
        },
        created: function () {

        },
        run: function () {

        },
    };

});