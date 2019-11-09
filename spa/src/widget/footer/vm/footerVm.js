define(['text!widget/footer/template/footer.html'], function(templateCode) {

    var data = {
        brand: '© JS Rails 2019',
        rightLinks: [
            {
                title: 'dist',
                url: 'http://dist.rails.js',
            },
            {
                title: 'src',
                url: 'http://src.rails.js',
            },
            {
                title: 'clean',
                url: 'http://rails.js/src/clean.html',
            },
        ],
    };

    return {
        el: '#app-footer',
        data: data,
        created: function () {
            $('#app-footer').html(templateCode);
        }
    };

});