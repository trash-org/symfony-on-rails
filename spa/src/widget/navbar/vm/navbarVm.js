define(['text!widget/navbar/template/navbar.html', 'jrails/kernel/container', 'jquery'], function(templateCode, container, $) {

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
            $('#app-navbar').html(templateCode);
            helper.update();
            container.event.registerHandler(['user.auth.login', 'user.auth.logout'], helper.update);
        },
    };

});