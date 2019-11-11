define([
    'jrails/kernel/container',
    'module/person/service/personService',
    'jrails/notify/notifyService',
    'jrails/spa/router',
    'module/user/lang/ru/auth',

    //

    'text!module/person/template/view.html',
], function(
    container,
    personService,
    notifyService,
    spaRouter,
    authLang
) {

    var data = {
        entity: {},
    };

    return {

        el: '#app-person-view',
        data: data,
        templateFile: 'module/person/template/view.html',
        access: function () {
            return {
                auth: '@',
            };
        },
        created: function () {

        },
        run: function () {
            personService.oneSelf().then(function (entity) {
                //d(entity);
                data.entity = entity;
            });
        },
    };

});