space('bundle.module.app.view.navbarTemplate', function() {

    return {
        template: function () {
            return '<div class="container">\n' +
                '        <div class="navbar-header">\n' +
                '            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">\n' +
                '                <span class="sr-only">Toggle navigation</span>\n' +
                '                <span class="icon-bar"></span>\n' +
                '                <span class="icon-bar"></span>\n' +
                '                <span class="icon-bar"></span>\n' +
                '            </button>\n' +
                '            <a class="navbar-brand" href="#">Project name</a>\n' +
                '        </div>\n' +
                '        <div class="collapse navbar-collapse">\n' +
                '            <ul class="nav navbar-nav">\n' +
                '                <li><a href="#bskit">BS3 UIKit</a></li>\n' +
                '                <li><a href="#contact">Contact</a></li>\n' +
                '                <li><a href="#todo">Todo</a></li>\n' +
                '            </ul>\n' +
                '            <ul class="nav navbar-nav navbar-right">\n' +
                '                <li v-if=" ! isLogin"><a href="#user/auth">Auth</a></li>\n' +
                '                <li v-if="isLogin" class="dropdown">\n' +
                '                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{username}} <b class="caret"></b></a>\n' +
                '                    <ul class="dropdown-menu">\n' +
                '                        <li v-if="isLogin"><a href="#person/view">Person</a></li>\n' +
                '                        <li v-if="isLogin"><a href="#user/logout">Logout</a></li>\n' +
                '                    </ul>\n' +
                '                </li>\n' +
                '            </ul>\n' +
                '        </div>\n' +
                '    </div>';
        }
    };
});