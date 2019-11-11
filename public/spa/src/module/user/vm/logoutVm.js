define([
    'jrails/kernel/container',
    'module/user/service/authService',
    'jrails/notify/notifyService',
    'jrails/spa/router',
    'module/user/lang/ru/auth',
], function(
    container,
    authService,
    notifyService,
    spaRouter,
    authLang
) {

    return {

        data: {},
        methods: {
            out: function (event) {

            },
        },
        run: function () {
            authService.logout();
            notifyService.success(authLang.successLogoutMessage);
            spaRouter.goHome();
        },
        access: function () {
            return {
                auth: '@',
            };
        },
    };

});