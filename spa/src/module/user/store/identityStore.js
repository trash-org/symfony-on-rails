space('bundle.module.user.store.identityStore', function() {

    return bundle.helper.class.extends(bundle.domain.baseLocalStorage, {

        storageKey: 'user.identity_1',

    });

});
