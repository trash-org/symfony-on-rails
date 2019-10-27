space('bundle.module.user.service.authService', function() {

    var identityStore = bundle.module.user.store.identityStore;

    return {

        authRequired: function () {
            container.event.trigger('user.auth.authRequired');
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
            var promise = container.restClient.post('auth', loginDto);
            promise.then(function (identity) {
                identityStore.set(identity);
                container.event.trigger('user.auth.login', identity);
            });
            return promise;
        },

        logout: function () {
            identityStore.set(null);
            container.event.trigger('user.auth.logout');
            //module.user.store.authStore.identity = null;
        },

    };

});
