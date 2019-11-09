define(['text!module/bskit/template/all.html'], function(templateCode) {

    var data = {
        collection: [
            {
                title: 'button',
                id: 'button',
            },
            {
                title: 'container',
                id: 'container',
            },
            {
                title: 'dialog',
                id: 'dialog',
            },
            {
                title: 'dropdown',
                id: 'dropdown',
            },
            {
                title: 'form',
                id: 'form',
            },
            {
                title: 'indicator',
                id: 'indicator',
            },
            {
                title: 'modal',
                id: 'modal',
            },
            {
                title: 'nav',
                id: 'nav',
            },
            {
                title: 'navbar',
                id: 'navbar',
            },
            {
                title: 'notification',
                id: 'notification',
            },
            {
                title: 'progressbar',
                id: 'progressbar',
            },
            {
                title: 'table',
                id: 'table',
            },
            {
                title: 'typography',
                id: 'typography',
            },
            {
                title: 'thumbnail',
                id: 'thumbnail',
            },
            {
                title: 'label',
                id: 'label',
            },
            {
                title: 'badge',
                id: 'badge',
            },
        ],
    };

    return {
        el: '#app-bskit-all',
        data: data,
        //template: templateCode,
        templateFile: 'module/bskit/template/all.html',
    };

});
