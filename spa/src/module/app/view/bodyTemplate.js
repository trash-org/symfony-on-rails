space('bundle.module.app.view.bodyTemplate', function() {

    return {
        template: function () {
            return '<div id="app-navbar" class="navbar navbar-inverse" role="navigation"></div>\n' +
                '<div class="container">\n' +
                '    <div id="app">\n' +
                '        <div id="app-main-index" class="page-layer jumbotron" style="display: none">\n' +
                '            <h1>main</h1>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '    <hr/>\n' +
                '    <div class="footer" id="app-footer"></div>\n' +
                '</div>';
        }
    };

});