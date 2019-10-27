space('bundle.module.app.controller.navbarController', function() {

    var data = {
        isLogin: false,
        username: '',
    };

    var helper = {
        update: function () {
            data.isLogin = container.authService.isLogin();
            data.username = data.isLogin ? container.authService.getIdentity().login : {};
        },
    };

    return {
        el: '#app-navbar',
        data: data,
        created: function () {
            $('#app-navbar').html(bundle.module.app.view.navbarTemplate.template());
            helper.update();
            container.event.registerHandler(['user.auth.login', 'user.auth.logout'], helper.update);
        },
    };

});