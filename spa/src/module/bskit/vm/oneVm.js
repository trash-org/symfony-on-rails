define([
    'jrails/spa/query',
    'text!module/bskit/template/one.html',

    //

    'text!module/bskit/template/example/badge.html',
    'text!module/bskit/template/example/button.html',
    'text!module/bskit/template/example/container.html',
    'text!module/bskit/template/example/dialog.html',
    'text!module/bskit/template/example/dropdown.html',
    'text!module/bskit/template/example/form.html',
    'text!module/bskit/template/example/indicator.html',
    'text!module/bskit/template/example/label.html',
    'text!module/bskit/template/example/modal.html',
    'text!module/bskit/template/example/nav.html',
    'text!module/bskit/template/example/navbar.html',
    'text!module/bskit/template/example/notification.html',
    'text!module/bskit/template/example/progressbar.html',
    'text!module/bskit/template/example/table.html',
    'text!module/bskit/template/example/thumbnail.html',
    'text!module/bskit/template/example/typography.html',
], function(spaQuery) {

    var data = {};

    return {
        el: '#app-bskit-one',
        data: data,
        templateFile: 'module/bskit/template/one.html',
        onReady: function () {
            var self = this;
            var query = spaQuery.get();
            var templateFileName = 'text!module/bskit/template/example/'+query.id+'.html';
            $(this.el).html('...');
            require([templateFileName], function (templateHtml) {
                $(self.el).html(templateHtml);
            });
        }
    };

});
