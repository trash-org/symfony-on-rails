define(['module/user/store/identityStore', 'jrails/kernel/container', 'jrails/event/eventService', 'jrails/rest/client'], function(identityStore, container, eventService, restClient) {

    return {

        authRequired: function () {
            eventService.trigger('user.auth.authRequired');
        },

        getIdentity: function () {
            var identity = identityStore.get();
            if(_.isEmpty(identity)) {
                return null;
            }
            return identity;
        },

        getToken: function () {
            var identity = identityStore.get();
            if(_.isEmpty(identity)) {
                return null;
            }
            return identity.token;
        },

        isLogin: function () {
            var identity = identityStore.get();
            return ! _.isEmpty(identity);
        },

        auth: function (loginDto) {
            //console.log(restClient);
            var promise = restClient.post('auth', loginDto);
            promise.then(function (identity) {
                identityStore.set(identity);
                eventService.trigger('user.auth.login', identity);
            });
            return promise;
        },

        logout: function () {
            identityStore.set(null);
            eventService.trigger('user.auth.logout');
            //module.user.store.authStore.identity = null;
        },

    };

});
