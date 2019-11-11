define([
    'jrails/kernel/container',
    'module/user/service/authService',
    'jrails/notify/notifyService',
    'jrails/spa/router',
    'module/user/lang/ru/auth',

    //

    'text!module/user/template/auth.html',
], function(
    container,
    authService,
    notifyService,
    spaRouter,
    authLang
) {

    var data = {
        entity: {},
        errors: {
            login: '',
            password: '',
        },
    };

    return {

        el: '#app-user-auth',
        data: data,
        depends: [
            //'bundle.module.user.store.authStore',
        ],
        templateFile: 'module/user/template/auth.html',
        methods: {
            auth: function (event) {
                var promise = authService.auth(data.entity);
                promise.then(function (identity) {
                    data.entity = {};
                    spaRouter.go();

                    console.log(identity);
                    notifyService.success(authLang.successAuthorizationMessage);
                }).catch(function (err) {
                    if(err.status === 422) {
                        data.errors = {};
                        for(var k in err.responseJSON) {
                            var fieldName = err.responseJSON[k].field;
                            var fieldMessage = err.responseJSON[k].message;
                            data.errors[fieldName] = fieldMessage;
                            //console.log([fieldName, fieldMessage]);
                        }
                        console.log(data.errors);
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