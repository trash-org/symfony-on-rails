space('bundle.module.contact.controller.allController', function() {

    return {

        run: function (request) {

        },

        onLoadDepends: function (request) {
            var value = {
                collection: [
                    {
                        id: 123,
                        title: 'item 123',
                        content: '',
                    },
                    {
                        id: 456,
                        title: 'item 456',
                        content: '',
                    },
                ],
            };
            var moduleElement = bundle.spa.layer.getModuleLayer(request);
            bundle.spa.template.compileElement(moduleElement, value);
        },

    };

});