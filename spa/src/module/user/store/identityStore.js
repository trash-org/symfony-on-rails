define(['jrails/helper/class', 'jrails/domain/baseLocalStorage'], function(classHelper, baseLocalStorage) {

    return classHelper.extends(baseLocalStorage, {

        storageKey: 'user.identity_1',

    });

});
