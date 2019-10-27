/**
Included files:
./node_modules/jrails/kernel/namespace.js
./node_modules/jrails/kernel/container.js
./node_modules/jrails/kernel/bootstrap.js
./node_modules/jrails/kernel/func.js
./node_modules/jrails/helper/ajax.js
./node_modules/jrails/helper/array.js
./node_modules/jrails/helper/class.js
./node_modules/jrails/helper/func.js
./node_modules/jrails/helper/jquery.js
./node_modules/jrails/helper/php.js
./node_modules/jrails/helper/url.js
./node_modules/jrails/helper/value.js
./node_modules/jrails/event/eventService.js
./node_modules/jrails/ui/baseElementService.js
./node_modules/jrails/ui/baseModule.js
./node_modules/jrails/notify/notifyService.js
./node_modules/jrails/notify/notifyTypeEnum.js
./node_modules/jrails/notify/driver/toastrDriver.js
./node_modules/jrails/domain/baseLocalStorage.js
./node_modules/jrails/domain/baseMemoryStore.js
./node_modules/jrails/rest/client.js
./node_modules/jrails/legalbet/bootstrap.js
./node_modules/jrails/legalbet/config.js
./node_modules/jrails/legalbet/notifyDriver.js
./node_modules/jrails/spa/layer.js
./node_modules/jrails/spa/router.js
./node_modules/jrails/vue/loader.js
./node_modules/jrails/vue/vm.js
./node_modules/jrails/bootstrap/modal/modalService.js
*/

/**
 * Работа с пространствами имен
 *
 * Можно объявлять, получать пространства.
 * Пространства имен нужны для иерархичного расположения кода.
 * Есть бандл, это самодостаточный модуль, который содержит в себе все неоходимое для своей работы.
 * В бандле могут распологаться хэлперы, сервисы, хранилища, виджеты, драйвера...
 * В плоском списке содержать разные типы классов неудобно,
 * но можно легко выстроить иерархию, например:
 * - user
 *     - service
 *         - authService
 *         - registrationService
 *         - personService
 *     - helper
 *         - loginHelper
 *         - tokenHelper
 *     - store
 *         - identityStore
 *         - personStore
 *     - widget
 *         - avatarWidget
 * - notify
 *     - service
 *         - notifyService
 *     - driver
 *         - sms
 *             - smscDriver
 *             - a1Driver
 *             - beelineDriver
 *         - notify
 *             - pushDriver
 *             - socketDriver
 *             - firebaseDriver
 *
 * `user` и `notify` - это бандлы.
 *
 * notify.driver.sms.beelineDriver - это полное имя класса драйвера для отправки СМС через Beeline
 * 
 * Аналог "use" из PHP:
 *     var ArrayHelper = bundle.helper.array;
 *
 * Получить любой класс можно так:
 *     namespace.get('bundle.helper.url').setUrl('?post=123');
 * Для прозрачности кода, лучше обращаться к классам явно:
 *     bundle.helper.url.setUrl('?post=123');
 * Составные части:
 *     bundle.helper.url - полное имя класса
 *     bundle.helper - пространство имен
 *     setUrl - метод класса
 */

(function() {

    var registry = {
        isDomLoaded: false,
        classList: {},
        onDomLoaded: function (func) {

            var callback = function () {
                var classDefinition = func();
                if(_.isObject(classDefinition) && _.isFunction(classDefinition._onLoad)) {
                    classDefinition._onLoad();
                }
            };

            if(this.isDomLoaded) {
                callback();
            } else {
                document.addEventListener('DOMContentLoaded', callback);
            }
        },
        onWindowLoad: function() {
            registry.isDomLoaded = true;
            //console.log(registry.classList);
        },
        use: function (className) {
            var func = _.get(registry.classList, className);
            if(_.isFunction(func)) {
                func = func();
                _.set(registry.classList, className, func);
            }
            return func;
        },
        define: function (funcOrClassName, func) {
            if(_.isFunction(funcOrClassName)) {
                registry.onDomLoaded(funcOrClassName);
            } else if(_.isString(funcOrClassName) && _.isFunction(func)) {
                registry.onDomLoaded(function() {
                    //var args = [];
                    //var classDefinition = func.apply({}, args);
                    var classDefinition = func();
                    //classList[funcOrClassName] = classDefinition;
                    _.set(window, funcOrClassName, classDefinition);
                    _.set(registry.classList, funcOrClassName, classDefinition);
                });

            }

            //registry.classList[funcOrClassName] = func;
        },
    };

    window.addEventListener('load', registry.onWindowLoad);
    window.use = registry.use;
    window.space = registry.define;

})();

space('bundle.kernel.loader', function() {

    var store = {
        loaded: {},
        aliases: {},
    };

    var helper = {
        isDefined: function (namespaceArray, object) {
            for (var key in namespaceArray) {
                var item = namespaceArray[key];
                if (typeof object[item] === "object") {
                    object = object[item];
                } else if(typeof object[item] === "undefined") {
                    return false;
                }
            }
            return true;
        },
        define: function (namespaceArray, object, value) {
            for (var key in namespaceArray) {
                var item = namespaceArray[key];
                if (typeof object[item] !== "object") {
                    object[item] = {};
                }
                object = object[item];
            }
            object = value;
        },
        forgeNamespaceRecursive: function (namespaceArray, object) {
            for (var key in namespaceArray) {
                var item = namespaceArray[key];
                if (typeof object[item] !== "object") {
                    object[item] = {};
                }
                object = object[item];
            }
            return object;
        },

        /**
         * Получить значение по пути
         * @param namespace
         * @returns {*}
         */
        get: function(namespace) {
            //namespace = this.getAlias(namespace);
            var arr = namespace.split('.');
            return helper.forgeNamespaceRecursive(arr, window);
        },

    };

    return {
        /**
         * Объявлено ли пространство имен
         * @param path путь
         * @param value в каком значении искать
         * @returns {*|boolean}
         */
        isDefined: function(path, value) {
            //path = this.getAlias(path);
            value = value === undefined ? window : value;
            //value = bundle.helper.value.default(value, window);
            var arr = path.split('.');
            return helper.isDefined(arr, value);
        },
        _getAlias: function (className) {
            for(var i in store.aliases) {
                var from = i;
                var to = store.aliases[i];
                var isMatch = className.indexOf(from + '.') === 0;
                if(isMatch) {
                    return {
                        from: from,
                        to: to,
                    };
                }
            }
            return false;
        },

        setAlias: function (from, to) {
            store.aliases[from] = to;
        },

        getAlias: function (className) {
            var alias = this._getAlias(className);
            if(alias) {
                className = alias.to + className.substr(alias.from.length);
            }
            return className;
        },

        requireClasses: function(classesNameSource, callback) {
            for(var k in classesNameSource) {
                var className = classesNameSource[k];
                this.requireClass(className);
            }
        },

        requireClass: function(classNameSource, callback) {
            var className = classNameSource;
            callback = _.defaultTo(callback, function () {});
            if(this.isDefined(className)) {
                callback();
                return className;
            }
            className = this.getAlias(className);
            if(this.isDefined(className)) {
                callback();
                return className;
            }
            var scriptClassArr = className.split('.');
            var scriptUrl = '/' + scriptClassArr.join('/') + '.js';
            if(store.loaded[scriptUrl] === true) {
                callback();
                return className;
            }
            this.requireScript(scriptUrl, callback);
            store.loaded[scriptUrl] = true;
            console.info('Script loaded "' + scriptUrl + '"!');
            return helper.get(classNameSource);
        },

        requireScript: function(url, callback) {
            jQuery.ajax({
                url: url,
                dataType: 'script',
                complete: callback,
                async: true
            });
            //$('body').append('<script src="' + url + '"></script>');
        },

    };

});

space(function() {

    /**
     * Контейнер
     */
    window.container =  {
        /**
         * Создать экземпляр объекта
         *
         * @param className класс
         * @param attributes назначаемые атрибуты
         * @param params параметры конструктора
         * @returns {Object}
         */
        instance: function (className, attributes, params) {
            if(_.isString(className)) {
                className = use(className);
            }
            return bundle.helper.class.create(className, attributes, params);
        },

        /**
         * Объявлен ли класс в контейнере
         *
         * @param className
         * @returns {Boolean}
         */
        has: function (className) {
            return bundle.kernel.loader.isDefined(className, this);
        },

    };

});
space('bundle.kernel.bootstrap', function() {

    /**
     * Ядро приложения
     *
     * Запускается 1 раз при запуске приложения
     */
    return {
        /**
         * Регистрация сервисов в контейнере
         */
        initContainer: function () {
            //container.env = bundle.env.envService;
            //container.log = bundle.log.logService;
        },

        /**
         * Конфигурация приложения
         */
        initConfig: function () {
            /** Конфигурация приложения */
        },

        /**
         * Запуск ядра приложения
         * @param params
         */
        run: function (params) {
            this.initContainer();
            this.initConfig();
            console.info('default kernel launch');

            /** Запуск приложения */
            //app.run();
        }
    };

});
space(function() {

    /**
     * Функция вывода данных в консоль
     */
    window.dump = function(value) {
        console.log(value);
    };
    window.d = window.dump;

});

space('bundle.helper.ajax', function() {

    /**
     * Работа с AJAX-запросами
     */
    return {

        errorCallback: function (jqXHR, exception) {
            var msg = window.bundle.helper.ajax.getErrorMessage(jqXHR, exception);
            container.notify.error('Произошла ошибка запроса!' + "<br/>" + msg);
        },

        /**
         * Подготовка запроса
         *
         * По умолчанию, при ошибке запроса,
         * пользователю будет показано всплывающее уведомление с ошибкой
         *
         * @param request объект запроса
         * @returns {*}
         */
        prepareRequest: function(request) {
            var complete = function () {
                //container.loader.hide();
            };
            if(!bundle.helper.php.is_function(request.error)) {
                request.error = this.errorCallback;
            }
            if(!bundle.helper.php.is_function(request.complete)) {
                request.complete = complete;
            }
            request.dataType = bundle.helper.value.default(request.dataType, 'json');
            return request;
        },

        /**
         * Оправить AJAX-запрос с ограничением частоты вызова
         *
         * Отложенная отправка запроса нужна для предотвращения посылки множества бессмысленных запросв
         * и снижения нагрузки на сервер при промежуточных изменениях.
         * Например, при перемещении ползунка, значения инпутов обновляются очень быстро.
         *
         * @param request объект запроса
         * @param groupName имя группы схожих запросов
         * @param interval интервал времени, регулирующий частоту вызовов
         */
        sendAtInterval: function(request, groupName, interval) {
            if(!_.isInteger(interval)) {
                interval = 1000;
            }
            interval = bundle.helper.value.default(interval, 1000);
            var func = function () {
                return bundle.helper.ajax.send(request);
            };
            bundle.helper.func.callAtInterval(groupName, func, interval);
        },

        /**
         * Оправить AJAX-запрос
         * @param request
         * @returns {*}
         */
        send: function(request) {
            //container.loader.show();

            var cloneRequest = _.clone(request);
            this.prepareRequest(cloneRequest);

            var promiseCallback = function(resolve,reject){
                cloneRequest.success = function(data) {
                    if(_.isFunction(request.success)) {
                        request.success(data);
                    }
                    resolve(data);
                };
                cloneRequest.error = function(data) {
                    if(_.isFunction(request.error)) {
                        request.error(data);
                    }
                    reject(data);
                };
                $.ajax(cloneRequest);
            };

            var promise = new Promise(promiseCallback);
            return promise;
        },

        /**
         * Полученние сообщения об ошибке
         * @param jqXHR
         * @param exception
         * @returns {string}
         */
        getErrorMessage: function(jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            return msg;
        },
    };

});
space('bundle.helper.localStorage', function () {

    /**
     * Работа с Local Storage
     */
    return {

        get: function (key, defaultValue) {
            var data = null;
            var dataJson = localStorage.getItem(key);
            if(! _.isEmpty(dataJson)) {
                data = JSON.parse(dataJson);
            }
            data = _.defaultTo(data, defaultValue);
            return data;
        },

        set: function (key, data) {
            var dataJson = JSON.stringify(data);
            localStorage.setItem(key, dataJson);
        },

        remove: function (key) {
            localStorage.removeItem(key);
        },

    };

});

space('bundle.helper.dom', function() {

    /**
     * Работа с DOM
     */
    return {

        appendToBody: function (element) {
            var bodyElement = $('body');
            bodyElement.append($(element));
        },

        bindEventForList: function (elements, eventName) {
            elements.each(function (index, value) {
                bundle.helper.dom.bindEvent(this, eventName);
            });
        },

        bindEventForAttribute: function (jElement, eventName, attributeName) {
            var aName = attributeName.substr(2);
            var handler = function (params) {
                var compiled = bundle.spa.template.compile(jElement.attr(attributeName), params);
                if (aName === 'html') {
                    jElement.html(compiled);
                } else {
                    jElement.attr(aName, compiled);
                }
            };
            container.event.registerHandler(eventName, handler);
        },

        bindEvent: function (element, eventName) {
            var jElement = $(element);
            var attributes = bundle.helper.dom.getAttributes(element);
            $.each(attributes, function(attributeName, value) {
                var isMatch = attributeName.indexOf('m-') === 0;
                if(isMatch) {
                    bundle.helper.dom.bindEventForAttribute(jElement, eventName, attributeName);
                }
            });
        },

        getAttributes: function (element) {
            var attrs = {};
            $.each(element.attributes, function() {
                if(this.specified) {
                    attrs[this.name] = this.value;
                    //console.log(this.name, this.value);
                }
            });
            return attrs;
        },

    };

});

space('bundle.helper.string', function() {

    /**
     * Работа со строками
     */
    return {

        escapeHtml: function (unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },

        /*unescapeHtml: function (safe) {
            return safe
                .replace("&amp;", /&/g)
                .replace("&lt;", /</g)
                .replace("&gt;", />/g)
                .replace("&quot;", /"/g)
                .replace("&#039;", /'/g);
        },*/

        unescapeHtml: function (safe) {
            return safe.replace(/&amp;/g, '&')
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>')
                .replace(/&quot;/g, '"')
                .replace(/&#039;/g, "'");
        }

    };

});

space('bundle.helper.array', function() {

    /**
     * Работа с массивами и объектами
     */
    return {

        /**
         * Получить уникальные ключи объекта
         * @param keyList
         * @returns {*}
         */
        uniqueFilter: function(keyList) {
            keyList = keyList.filter(function(itm, i, a) {
                return i == a.indexOf(itm);
            });
            return keyList;
        },

        /**
         * Удалить значение из массива
         * @param arr
         * @param value
         * @returns {boolean}
         */
        removeByValue: function(arr, value) {
            var index = arr.indexOf(value);
            if (index >= 0) {
                arr.splice( index, 1 );
                return true;
            }
            return false;
        },

        /**
         * Получить ключи объекта
         * @param object
         * @returns {[]}
         * @deprecated use _.keys
         */
        getKeys: function(object) {
            return _.keys(object);
            /*var keys = [];
            for (var k in object) keys.push(k);
            return keys;*/
        },

        /**
         * Слить объекты
         * @param from
         * @param to
         */
        merge: function(from, to) {
            for(var k in from) {
                to[k] = from[k];
            }
        },
    };

});
space('bundle.helper.class', function() {

    /**
     * Работа с классами
     */
    return {

        /**
         * Выполнить метод в массиве классов поочередно
         *
         * @param classes массив классов
         * @param method имя вызываемого метода
         * @param params параметры вызываемого метода
         */
        callMethodInClasses: function(classes, method, params) {
            var keys = _.keys(classes);
            for(var i in keys) {
                var key = keys[i];
                var controller = classes[key];
                controller[method](params);
            }
        },

        /**
         * Получить методы объекта
         *
         * @param object
         * @returns {[]}
         * @deprecated use _.functions
         */
        getMethods: function(object) {
            var methods = [];
            var keys = _.keys(object);
            for(var key in keys) {
                var item = keys[key];
                if(bundle.helper.php.is_function(object[item])) {
                    methods.push(item);
                }
            }
            return methods;
        },

        /**
         * Получить публичные методы объекта
         *
         * @param object
         * @returns {[]}
         */
        getPublicMethods: function(object) {
            var methods = [];
            var allMethods = _.functions(object);
            for(var key in allMethods) {
                var method = allMethods[key];
                if(method[0] !== '_') {
                    methods.push(method);
                }
            }
            return methods;
        },

        /**
         * Наследование объекта от родительского
         *
         * @param parent объект родитель
         * @param newClass объект потомок
         * @returns {*}
         */
        extends: function (parent, newClass, interfaceClass) {
            var instance = _.clone(parent);
            instance = _.assign(instance, newClass);
            if(interfaceClass !== undefined) {
                this.checkInterface(instance, interfaceClass);
            }
            //bundle.helper.class.setAttributes(instance, newClass);
            //instance.parent = parent;
            return instance;
        },

        /**
         * Проверка принадлежности объекта к интерфейсу.
         *
         * Если проверяемом объекте описаны не все методы из интерфейса,
         * то вызывается исключение.
         *
         * @param object
         * @param interfaceClass
         * @return {boolean}
         * @throws
         */
        checkInterface: function (object, interfaceClass) {
            var difference = this.checkInterfaceDiff(object, interfaceClass);
            if( ! _.isEmpty(difference)) {
                var message = 'Methods "' + difference.join(', ') + '" not found!';
                throw message;
            }
            return true;
        },

        /**
         * Получение списка недостающих методов.
         *
         * Если все методы, описанные в интерфейсе присутствуют,
         * то возвращается пустой массив.
         *
         * @param object проверяемый объект
         * @param interfaceClass интерфейс
         * @return {Array}
         */
        checkInterfaceDiff: function (object, interfaceClass) {
            if( ! _.isObject(object)) {
                throw 'Source class is not object!';
            }
            if( ! _.isObject(interfaceClass)) {
                throw 'Interface is not object!';
            }
            var methodNames = _.functions(interfaceClass);
            var difference = _.difference(methodNames, _.functions(object));
            return difference;
        },

        /**
         * Проверка принадлежности объекта к интерфейсу
         *
         * @param object проверяемый объект
         * @param interfaceClass интерфейс
         * @returns {boolean}
         */
        isInstanceOf: function (object, interfaceClass) {
            var difference = this.checkInterfaceDiff(object, interfaceClass);
            return _.isEmpty(difference);
        },

        /**
         * Создать новый экземляр объекта
         *
         * @param className класс
         * @param attributes назначаемые атрибуты
         * @param params параметры, передаваемые конструктору объекта
         * @returns {*}
         */
        create: function (className, attributes, params) {
            var instance = _.clone(className);
            instance = _.assign(instance, attributes);
            //bundle.helper.class.setAttributes(instance, attributes);
            this.callConstructor(instance, params);
            return instance;
        },

        /**
         * Создать новый экземляр объекта
         *
         * @param className класс
         * @param params параметры, передаваемые конструктору объекта
         * @returns {*}
         */
        createInstance: function(className, params) {
            var instance = _.clone(className);
            this.callConstructor(instance, params);
            return instance;
        },

        /**
         * Клонировать объект
         *
         * @param className
         * @returns {*}
         */
        /*clone: function(className) {
            return _.clone(className);
        },*/

        /**
         * Присвоить объекту атрибуты
         *
         * @param instance
         * @param attributes
         */
        setAttributes: function(instance, attributes) {
            return  _.assign(instance, attributes);
            /*if (typeof attributes === 'object') {
                for (var k in attributes) {
                    instance[k] = attributes[k];
                }
            }*/
        },

        /**
         * Вызвать метод конструктора объекта
         *
         * @param instance
         * @param params
         */
        callConstructor: function(instance, params) {
            if(bundle.helper.php.method_exists(instance, '__construct')) {
                instance.__construct(params);
            }
        },
    };

});
space('bundle.helper.func', function() {

    var callAtIntervalHelper = {

        _lastTime: {},
        _timeoutId: {},

        refreshTimeout: function(name) {
            this._lastTime[name] = bundle.helper.value.nowTimestamp();
        },

        issetTimeout: function(name) {
            return ! _.isEmpty(this._lastTime[name]);
        },

        refreshCall: function(name, func, interval) {
            clearTimeout(this._timeoutId[name]);
            this._timeoutId[name] = setTimeout(func,interval);
        },

    };

    /**
     * Работа с функциями
     */
    return {

        /**
         * Вызвать метод не чаще, чем указано в интервале.
         *
         * Если интервал указан 1000, то метод будет вызван не чаще,
         * чем 1 раз в 1 секунду.
         * Если метод вызван 2 раза за секунду, то выполнится последний метод,
         * предыдущие методы удалятся
         *
         * @param name имя
         * @param func вызываемая функция
         * @param interval интервал в милисекундах
         * @returns {*}
         */
        callAtInterval: function(name, func, interval) {
            if(! callAtIntervalHelper.issetTimeout(name)) {
                callAtIntervalHelper.refreshCall(name, func, interval);
                callAtIntervalHelper.refreshTimeout(name);
                return false;
            }
            callAtIntervalHelper.refreshTimeout(name);
        },
    };

});
space('bundle.helper.jqueryUi', function() {

    /**
     * Работа с JQuery
     */
    window.bundle.helper.jquery = {

    };

    /**
     * Работа с JQuery UI
     */
    return {

        eventTrigger: function (widget, eventType, data) {
            var names = this.getElementEventNames(widget, eventType);
            names.forEach(function(item) {
                container.event.trigger(item, data);
            });
        },

        getElementEventNames: function (widget, eventType) {
            var elementId = widget.element.attr('id');
            var names = [];
            if(!bundle.helper.php.empty(elementId)) {
                names.push(widget.widgetEventPrefix+'.'+elementId+'.'+eventType);
            }
            names.push(widget.widgetEventPrefix+'.'+eventType);
            return names;
        },

    };

});
space('bundle.helper.php', function() {

    /**
     * Аналоги функций из PHP SPL
     */
    return {

        /**
         * Является ли целым числом
         * @param data
         */
        is_int: function (data) {
            return data === parseInt(data, 10);
        },

        /**
         * содержится ли ключ в объекте
         * @param key
         * @param array
         * @returns {number}
         */
        in_array: function (key, array) {
            return $.inArray(key, array) !== -1 ? 1 : 0;
        },

        /**
         * Получить тип данных
         * @param value
         * @returns {string}
         */
        get_type: function (value) {
            var type = null;
            if(this.is_function(value)) {
                return 'function';
            }
            if(this.is_object(value)) {
                return 'object';
            }
            if(this.is_array(value)) {
                return 'array';
            }
        },

        /**
         * Является ли функцией или методом
         * @param value
         * @returns {boolean}
         */
        is_function: function (value) {
            return typeof value === "function";
        },

        /**
         * Является ли объектом
         * @param value
         * @returns {boolean}
         */
        is_object: function (value) {
            if (value instanceof Array) {
                return false;
            } else {
                return (value !== null) && (typeof(value) === 'object');
            }
        },

        /**
         * Назначено ли значение
         * @param value
         * @returns {boolean}
         */
        isset: function (value) {
            //return element.length > 0;
            return typeof(value) !== "undefined" && value !== null;
        },

        /**
         * Является ли пустым
         * @param value
         * @returns {boolean|*}
         */
        empty: function (value) {
            if(typeof value === "undefined") {
                return true;
            }
            return (value === "" || value === 0 || value === "0" || value === null || value === false || (this.is_array(value) && value.length === 0));
        },

        /**
         * Является ли массивом
         * @param value
         * @returns {boolean}
         */
        is_array: function (value) {
            return (value instanceof Array);
        },

        /**
         * Существует ли метод в объекте
         * @param object
         * @param method
         * @returns {boolean}
         */
        method_exists: function (object, method) {
            return typeof object[method] === 'function';
        },
    };

});
space('bundle.helper.url', function() {

    /**
     * Работа с ссылками
     */
    return {

        /**
         * Изменить URL страницы без перезагрузки
         * @param url относительный URL
         */
        setUrl: function (url) {
            var state = {};
            var title = '';
            history.pushState(state, title, url);
        },
    };

});
space('bundle.helper.value', function() {

    /**
     * Работа с данными
     */
    return {

        /**
         * Получить значение по умолчанию, если значение не назначенное
         * @param value
         * @param defaultValue
         * @returns {*}
         */
        default: function(value, defaultValue) {
            return value === undefined ? defaultValue : value;
        },

        /**
         * Пустое ли значение
         * @param value
         * @returns {boolean}
         */
        isEmpty: function(value) {
            return value !== '' && value !== null && value !== [];
        },

        /**
         * Получить текущее время в виде TIMESTAMP
         * @returns {number}
         */
        nowTimestamp: function() {
            return ( new Date() ).getTime();
        },
        /*createInstance: function(className, params) {
            var instanceArray = FastClone.cloneArray([className]);
            var instance = instanceArray[0];
            this.callConstructor(instance, params);
            return instance;
        },
        setAttributes: function(instance, attributes) {
            if (typeof attributes === 'object') {
                for (var k in attributes) {
                    instance[k] = attributes[k];
                }
            }
        },
        callConstructor: function(instance, params) {
            if(bundle.helper.php.method_exists(instance, '__construct')) {
                instance.__construct(params);
            }
        },*/
    };

});
space('bundle.event.eventService', function() {

    /**
     * Работа с событиями
     */
    return {

        handlers: {},
        holdList: {},

        /**
         * Регистрация обработчика события
         *
         * @param eventName {String|Array} имя события или массив имен
         * @param handler обработчик (функция или класс с методом "run")
         */
        registerHandler: function(eventName, handler) {
            if(_.isArray(eventName)) {
                for(var k in eventName) {
                    var eventNameItem = eventName[k];
                    this.registerHandler(eventNameItem, handler);
                }
            }
            this._initEventArray(eventName);
            this.handlers[eventName].push(handler);
            console.info('Register handler (' + bundle.helper.php.get_type(handler) + ') for event "' + eventName + '"');
        },

        /**
         * Удаление обработчика события
         *
         * @param eventName имя события
         * @param handler обработчик (функция или класс с методом "run")
         */
        removeHandler: function(eventName, handler) {
            this._initEventArray(eventName);
            var isRemoved = bundle.helper.array.removeByValue(this.handlers[eventName], handler);
            if(isRemoved) {
                console.info('Remove handler for event "' + eventName + '"');
            }
        },

        /**
         * Отключить обработку события
         *
         * @param eventName имя события
         */
        hold: function(eventName) {
            this.holdList[eventName] = true;
        },

        /**
         * Включить обработку события
         *
         * @param eventName имя события
         */
        unHold: function(eventName) {
            this.holdList[eventName] = false;
        },

        /**
         * Отключена ли обработка события
         *
         * @param eventName имя события
         * @returns {boolean}
         */
        isHold: function(eventName) {
            return ! _.isEmpty(this.holdList[eventName]);
        },

        /**
         * Вызов события
         *
         * @param eventName имя события
         * @param params параметры события
         */
        trigger: function(eventName, params) {
            if(this.isHold(eventName)) {
                console.info('Event "' + eventName + '" is hold!');
                return;
            }
            this._initEventArray(eventName);
            var handlers = this.handlers[eventName];
            this._runHandlersForEvent(eventName, handlers, params);
        },

        _initEventArray: function(eventName) {
            if(!bundle.helper.php.isset(this.handlers[eventName])) {
                this.handlers[eventName] = [];
            }
        },

        _runHandlersForEvent: function (eventName, handlers, params) {
            if(bundle.helper.php.empty(handlers)) {
                console.info('Not found handlers for event "' + eventName + '"');
                return;
            }

            var self = this;
            handlers.forEach(function(handler) {
                self._runHandler(eventName, handler, params);
            });

            /*for (var key in handlers) {
                var handler = handlers[key];
                this._run(handler, params);
            }*/
        },
        _runHandler: function (eventName, handler, params) {
            if(bundle.helper.php.is_object(handler)) {
                handler.run(params);
                console.info('Run handler (object) for event "' + eventName + '"');
            } else if(bundle.helper.php.is_function(handler)) {
                handler(params);
                console.info('Run handler (function) for event "' + eventName + '"');
            }
        }
    };

});
space('bundle.ui.baseElementService', function() {

    /**
     * Визуальные элементы управления (кнопки, поля ввода)
     */
    return {

        id: null,
        instance: null,
        selector: null,

        /**
         * Метод конструктора
         * @param param
         * @private
         */
        __construct: function (param) {
            this.instance = $(this.selector);
            this._registerEvents();

            var self = this;
            this.on('click', function () {

                var elementId = self.instance.attr('id');
                if(bundle.helper.php.empty(elementId)) {
                    elementId = self.id;
                }
                if( ! bundle.helper.php.empty(elementId)) {
                    var event = {
                        target: self.instance,
                    };
                    container.event.trigger('baseElementService.'+elementId+'.click', event);
                }
                return false;

            });
            //dump(this.instance);
        },

        _getId: function() {

        },

        _registerEvents: function() {

        },

        /**
         * Назначить обработчик события
         * @param name имя события
         * @param handler обработчик (функция)
         */
        on: function(name, handler) {
            this.instance.on(name, handler);
        },

        /**
         * Вызвать обработчик события
         * @param name имя события
         */
        trigger: function(name) {
            this.instance.trigger(name);
        },

        /**
         * Показать элемент
         */
        show: function() {
            this.instance.show();
        },

        /**
         * Скрыть элемент
         */
        hide: function() {
            this.instance.hide();
        },

        /**
         * Сделать элемент доступным
         */
        enable: function() {
            this.instance.removeAttr('disabled');
        },

        /**
         * Сделать элемент не доступным
         */
        disable: function() {
            this.instance.attr('disabled','disabled');
        },

    };

});
space('bundle.ui.baseModule', function() {

    /**
     * Базовый класс модуля
     */
    return {

        controllerNamespace: null,

        /**
         * Инициализация контроллеров
         */
        initControllers: function () {
            bundle.helper.class.callMethodInClasses(this.controllerNamespace, 'init');
        },

    };

});
space('bundle.notify.notifyService', function() {

    /**
     * Работа с пользовательскими уведомлениями
     */
    return {

        /**
         * Драйвер показа уведомлений
         */
        driver: null,

        /**
         * Показать сообщение с информацией
         * @param message текст сообщения
         */
        info: function (message) {
            this.show(bundle.notify.notifyTypeEnum.info, message);
        },

        /**
         * Показать предупреждение
         * @param message текст сообщения
         */
        warning: function (message, options) {
            this.show(bundle.notify.notifyTypeEnum.info, message, options);
        },

        /**
         * Показать сообщение об успешной операции
         * @param message текст сообщения
         */
        success: function (message) {
            this.show(bundle.notify.notifyTypeEnum.success, message);
        },

        /**
         * Показать сообщение об ошибке
         * @param message текст сообщения
         */
        error: function (message) {
            this.show(bundle.notify.notifyTypeEnum.error, message);
        },

        /**
         * Показать сообщение о критической ошибке
         * @param message текст сообщения
         */
        danger: function (message) {
            this.show(bundle.notify.notifyTypeEnum.danger, message);
        },

        /**
         * Показать сообщение любого типа
         * @param type тип сообщения (перечнь типов смотреть в классе bundle.notify.notifyTypeEnum)
         * @param message текст сообщения
         */
        show: function (type, message) {
            this.driver.show({
                type: type,
                message: message,
            });
            /*intel.notify({
                status: type,
                text: message,
            });*/
        },

    };

});
space('bundle.notify.notifyTypeEnum', function() {

    /**
     * Список типов уведомлений
     */
    return {
        success: 'success',
        info: 'info',
        warning: 'warning',
        danger: 'danger',
        error: 'error',
    };

});
space('bundle.notify.driver.toastrDriver', function() {

    /**
     * Драйвер уведомлений для вендора toastr
     */
    return {

        options: {},

        /**
         * Показать сообщение любого типа
         * @param entity сущность уведомления
         */
        show: function (entity) {
            entity.options = _.defaultTo(entity.options, this.options);
            var method = toastr[entity.type];
            method(entity.message, entity.options);
        },

    };

});
space('bundle.domain.baseLocalStorage', function() {

    var localStorageHelper = bundle.helper.localStorage;

    /**
     * Базовый класс для хранилища состояния в Local Storage
     */
    return {

        storageKey: null,

        get: function (defaultValue) {
            return localStorageHelper.get(this.storageKey, defaultValue);
        },

        set: function (data) {
            localStorageHelper.set(this.storageKey, data);
        },

        remove: function () {
            localStorageHelper.remove(this.storageKey);
        },

    };

});
space('bundle.domain.baseMemoryStore', function() {

    /**
     * Базовый класс для хранилища состояния в ОЗУ
     */
    return {

        /**
         * Сохранить значение
         * @param key ключ
         * @param value данные
         */
        set: function (key, value) {
            this[key] = value;
        },

        /**
         * Получить значение
         * @param key ключ
         * @returns {*}
         */
        get: function (key) {
            return this[key];
        },

        /**
         * Установлено ли значение
         * @param key ключ
         * @returns {boolean}
         */
        has: function (key) {
            return typeof this[key] === 'undefined';
        },

        /**
         * Удалить значение
         * @param key ключ
         */
        remove: function (key) {
            delete this[key];
        },

    };

});
space('bundle.rest.client', function() {

    var helper = {

        prepareRequestAuthorization: function (request) {
            var token = container.authService.getToken();
            if(token) {
                request.headers.Authorization = token;
            }
        },

    };

    return  {

        baseUrl: null,

        __construct: function(params) {
            if(_.isEmpty(params.baseUrl)) {
                throw 'bundle.rest.client.__construct: baseUrl param not defined';
            }
            this.baseUrl = params.baseUrl;
        },

        get: function (url, query, headers) {
            var request = {
                url: url,
            };
            if(headers) {
                request.headers = _.defaultTo(headers, {});
            }
            return this.sendRequest(request);
        },

        post: function (url, data, headers) {
            var request = {
                url: url,
                type: 'POST',
                data: data,
            };
            if(headers) {
                request.headers = _.defaultTo(headers, {});
            }
            return this.sendRequest(request);
        },

        put: function (url, data, headers) {
            var request = {
                url: url,
                type: 'PUT',
                data: data,
            };
            if(headers) {
                request.headers = _.defaultTo(headers, {});
            }
            return this.sendRequest(request);
        },

        del: function (url, query, headers) {
            var request = {
                url: url,
                type: 'DELETE',
            };
            if(headers) {
                request.headers = _.defaultTo(headers, {});
            }
            return this.sendRequest(request);
        },

        /*setBaseUrl: function (baseUrl) {
            this.baseUrl = baseUrl;
        },*/

        sendRequest: function (requestSource) {
            var request = _.clone(requestSource);
            this.prepareRequest(request);
            var promiseCallback = function(resolve,reject){
                request.success = function(data) {
                    resolve(data);
                    container.event.trigger('api.request.send.success', data);
                };
                request.error = function(jqXHR) {
                    container.event.trigger('api.request.send.error', jqXHR);
                    reject(jqXHR);
                };
                $.ajax(request);
            };
            return new Promise(promiseCallback);
        },

        prepareRequest: function (request) {
            request.headers = _.defaultTo(request.headers, {});
            this.prepareRequestUrl(request);
            helper.prepareRequestAuthorization(request);
        },

        prepareRequestUrl: function (request) {
            request.url = this.baseUrl + '/' + request.url;
        },

        /**
         * Полученние сообщения об ошибке
         * @param response {*}
         * @returns {string}
         */
        getErrorMessage: function(response) {
            var msg = '';
            if (response.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (response.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (response.status == 500) {
                msg = 'Internal Server Error [500].';
            } else {
                msg = 'Uncaught Error.\n' + response.responseText;
            }
            return msg;
        },

    };

});
space('bundle.legalbet.component.bootstrap', function() {

    /**
     * Ядро приложения
     *
     * Запускается 1 раз при запуске приложения
     *
     * Todo: переименовать в bundle.legalbet.bootstrap
     */
    return {

        /**
         * Регистрация сервисов в контейнере
         */
        initContainer: function () {
            //container.cache = bundle.cache.cacheService;
            container.event = bundle.event.eventService;
            container.notify = bundle.notify.notifyService;
            //container.queue = bundle.queue.queueService;
            container.loader = container.instance(bundle.ui.baseElementService, {
                selector: '.js-loader',
            });
        },

        /**
         * Конфигурация приложения
         */
        initConfig: function () {
            /** Конфигурация приложения */
        },

        /**
         * Запуск ядра приложения
         * @param params
         */
        run: function (params) {
            bundle.kernel.bootstrap.run(params);
            this.initContainer();
            this.initConfig();
            console.info('legalbet kernel launch');
        }
    };

});
space('bundle.legalbet.config', function() {



});
space('bundle.notify.driver.notifyDriver', function() {

    /**
     * Драйвер уведомлений для Legalbet
     */
    return {

        options: {},

        /**
         * Показать сообщение любого типа
         * @param entity сущность уведомления
         */
        show: function (entity) {
            intel.notify({
                status: entity.type,
                text: entity.message,
            });
        },

    };

});
space('bundle.spa.layer', function() {

    /**
     *
     */
    return {

        wrapperId: 'app',
        wrapperInstance: null,

        getWrapperElement: function () {
            if( ! _.isObject(this.wrapperInstance)) {
                this.wrapperInstance = $('#' + this.wrapperId);
            }
            return this.wrapperInstance;
        },

        getElementId: function (id) {
            if(id) {
                return this.wrapperId + '-' + id;
            } else {
                return this.wrapperId;
            }
        },

        getModuleLayer: function (request) {
            var moduleElementId = this.getElementId(request.controller + '-' + request.action);
            return this.getWrapperElement().find('#' + moduleElementId);
        },

        has: function (request) {
            var layerWrapper = this.getModuleLayer(request);
            return layerWrapper.length;
        },

        show: function (request) {
            bundle.spa.layer.hideAll();
            var layerWrapper = this.getModuleLayer(request);
            layerWrapper.show();
        },

        add: function (data, request) {
            var moduleElementId = this.getElementId(request.controller + '-' + request.action);
            var layerHtml =
                '<div class="page-layer" id="' + moduleElementId + '">' +
                data +
                '</div>';
            this.getWrapperElement().append(layerHtml);
        },

        hideAll: function () {
            this.getWrapperElement().find('.page-layer').hide();
        },

    };

});

space('bundle.spa.template', function() {

    return {

        compileElement: function (moduleElement, params) {
            var template = moduleElement.html();
            var html = this.compile(template, params);
            moduleElement.html(html);
        },

        compile: function (template, params) {
            var templateHtml = bundle.helper.string.unescapeHtml(template);
            return _.template(templateHtml)(params);
        },

    };

});

space('bundle.spa.helper', function() {

    return {

        /*getVueInstance: function (definition) {
            var el = definition.el;
            if( ! bundle.vue.vm.has(el)) {
                var vueInstance = new Vue(definition);
                bundle.vue.vm.set(el, vueInstance);
            }
        },*/

        getClassName: function (request, type) {
            var className = 'bundle.module.' + request.controller + '.'+type+'.' + request.action + _.startCase(_.toLower(type));
            return className;
        },

        getTemplateUrl: function (request) {
            var templateUrl = '/src/' + request.path + '/' + request.controller + '/view/' + request.action + '.html';
            return templateUrl;
        },

        isTemplate: function (data) {
            return data.search(/<!DOCTYPE html>/g) === -1;
        },

        prepareRequest: function (request) {
            request.action = _.defaultTo(request.action, 'index');
            request.path = _.defaultTo(request.path, 'module');
            request.namespace = request.controller + '.' + request.action;
        },

        registerEventHandlers: function (request) {
            var moduleElement = bundle.spa.layer.getModuleLayer(request);
            var elements = moduleElement.find($("*"));
            bundle.helper.dom.bindEventForList(elements, 'bundle.module.contact.store.contactStore.update');
            /*container.event.registerHandler('bundle.module.contact.store.contactStore.update', function (contactEntity) {
                moduleElement.find('#title').html(contactEntity.title);
                moduleElement.find('#content').html(contactEntity.content);
                moduleElement.find('#delete-action').attr('href', contactEntity.deleteAction);
                moduleElement.find('#update-action').attr('href', contactEntity.updateAction);
            });*/
        },

    };



});


space('bundle.spa.module', function() {

    /**
     *
     */
    return {

        request: null,

        loadTemplate: function (request, callback) {
            var templateUrl = window.bundle.spa.helper.getTemplateUrl(request);
            $.ajax({
                url: templateUrl,
                success: function (data) {

                    callback();
                    if (window.bundle.spa.helper.isTemplate(data)) {
                        bundle.spa.layer.add(data, request);
                    }
                }
            });
        },

        loadDepends: function (request, controller) {
            if(_.isEmpty(controller.depends)) {
                controller.onLoadDepends(request);
                return;
            }
            var cbCount = 0;
            var cb = function () {
                cbCount++;
                if(cbCount === controller.depends.length) {
                    //d(cbCount);
                    controller.onLoadDepends(request);
                    controller.run(request);
                }
            };
            for(var k in controller.depends) {
                var dependClass = controller.depends[k];
                bundle.kernel.loader.requireClass(dependClass, cb);
            }
        },

        run: function (requestSource) {
            var request = _.clone(requestSource);
            this.request = request;
            bundle.spa.helper.prepareRequest(request);
            var callback = function () {
                var className = window.bundle.spa.helper.getClassName(request, 'controller');
                bundle.spa.layer.show(request);
                var cb = function () {
                    var controller = use(className);
                    if( ! _.isEmpty(controller)) {
                        if(_.isEmpty(controller.isInit)) {
                            controller.isInit = true;
                            bundle.spa.module.loadDepends(request, controller);
                        }
                    }
                    bundle.spa.helper.registerEventHandlers(request);
                };
                bundle.kernel.loader.requireClass(className, cb);
            };
            this.doRequest(request, callback);
        },

        doRequest: function (request, callback) {
            var isExists = bundle.spa.layer.has(request);
            if (isExists) {
                callback();
            } else {

                this.loadTemplate(request, callback);
            }
        },

    };

});


/*$("a").each(function(index, element) {
            $(element).click(function (event) {
                var el = $(event.target);
                var uri = el.attr('href');
                uri = _.trim(uri, '#/');
                uri = '/#' + uri;
                console.log(uri);
                bundle.helper.url.setUrl(uri);
                return false;
            });
        });*/
space('bundle.spa.router', function() {

    var store = {
        routes: {},
        routerInstance: null,
    };

    return {

        go: function (uri) {
            uri = _.defaultTo(uri, '');
            location.hash = '#' + uri;
        },

        goBack: function () {
            history.back();
        },

        goHome: function () {
            this.go();
        },

        addRoute: function (route, callback) {
            store.routes[route] = callback;
        },

        init: function () {
            store.routerInstance = Router(store.routes);
            store.routerInstance.init();
        },

    };

});
space('bundle.vue.loader', function() {

    var helper = {

        checkAccess: function (controller) {
            var access = controller.access();
            if(_.isEmpty(access)) {
                return true;
            }
            if(access.auth === '@' && ! container.authService.isLogin()) {
                bundle.module.user.service.authService.authRequired();
                return false;
            }
            if(access.auth === '?' && container.authService.isLogin()) {
                container.notify.info(lang.user.auth.alreadyAuthorizedMessage);
                bundle.spa.router.goBack();
                return false;
            }
            return true;
        },

        runController: function (controller, request) {
            var isAllow = false;
            if(_.isFunction(controller.access)) {
                isAllow = this.checkAccess(controller);
            }
            if(_.isFunction(controller.run) && isAllow) {
                controller.run(request);
            }
        },

    };

    return {

        request: null,

        loadTemplate: function (request, callback) {
            var templateUrl = window.bundle.spa.helper.getTemplateUrl(request);
            $.ajax({
                url: templateUrl,
                success: function (data) {
                    callback();
                    if (window.bundle.spa.helper.isTemplate(data)) {
                        bundle.spa.layer.add(data, request);
                    }
                }
            });
        },

        loadDepends: function (request, controller) {
            if(_.isEmpty(controller.depends)) {
                //d(controller);
                bundle.vue.vm.ensure(controller);
                //bundle.spa.helper.getVueInstance(controller);
                //controller.onLoadDepends(request);
                helper.runController(controller, request);
                return;
            }
            var cbCount = 0;
            var cb = function () {
                cbCount++;
                if(cbCount === controller.depends.length) {
                    //d(cbCount);
                    //d(controller);
                    //bundle.spa.helper.getVueInstance(controller);
                    bundle.vue.vm.ensure(controller);
                    //controller.onLoadDepends(request);
                    helper.runController(controller, request);
                }
            };
            for(var k in controller.depends) {
                var dependClass = controller.depends[k];
                if(dependClass.search(/\//g) !== -1) {
                    bundle.kernel.loader.requireScript(dependClass, cb);
                } else {
                    bundle.kernel.loader.requireClass(dependClass, cb);
                }
            }
        },

        run: function (requestSource) {
            //d(requestSource);
            var request = _.clone(requestSource);
            this.request = request;
            bundle.spa.helper.prepareRequest(request);
            var callback = function () {
                var className = window.bundle.spa.helper.getClassName(request, 'controller');
                bundle.spa.layer.show(request);
                var cb = function () {
                    var controller = use(className);
                    if( ! _.isEmpty(controller)) {
                        if(_.isEmpty(controller.isInit)) {
                            controller.isInit = true;
                            controller.el = '#app-'+request.controller+'-'+request.action;
                            bundle.vue.loader.loadDepends(request, controller);
                        }
                    }
                    bundle.spa.helper.registerEventHandlers(request);
                };
                if(bundle.kernel.loader.isDefined(className)) {
                    cb();
                } else {
                    bundle.kernel.loader.requireClass(className, cb);
                }
            };
            this.doRequest(request, callback);
        },

        doRequest: function (request, callback) {
            var isExists = bundle.spa.layer.has(request);
            if (isExists) {
                callback();
            } else {

                this.loadTemplate(request, callback);
            }
        },

    };

});
space('bundle.vue.vm', function() {

    var store = {};

    return {

        has: function (key) {
            return ! _.isEmpty(store[key]);
        },

        get: function (key) {
            return store[key];
        },

        set: function (key, definition) {
            store[key] = definition;
        },

        ensure: function (definition) {
            var instance;
            var selector = definition.el;
            if( ! bundle.vue.vm.has(selector)) {
                instance = new Vue(definition);
                this.set(selector, instance);
            }
            return instance;
        },

    };

});
space('bundle.module.bootstrap.modal.modalService', function() {

    var helper = {
        renderHeader: function (content) {
            var closeButtonHtml = '<button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">×</span></button>';
            var titleHeaderHtml = '<h4 class="modal-title">'+content+'</h4>';
            return '<div class="modal-header">'+closeButtonHtml+titleHeaderHtml+'</div>';
        },
        renderBody: function (content) {
            return  '<div class="modal-body">'+content+'</div>';
        },
        renderFooter: function (content) {
            return '<div class="modal-footer">'+content+'</div>';
        },
        renderModal: function (data) {
            var contentHtml = '';
            if(data.title) {
                contentHtml = contentHtml + helper.renderHeader(data.title);
            }
            if(data.body) {
                contentHtml = contentHtml + helper.renderBody(data.body);
            }
            if(data.foot) {
                contentHtml = contentHtml + helper.renderFooter(data.foot);
            }
            return '<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n' +
                '        <div class="modal-dialog" role="document">\n' +
                '            <div class="modal-content">'+contentHtml+'</div>\n' +
                '        </div>\n' +
                '    </div>';
        },
    };

    return {
        show: function (data, options) {
            var modalHtml = helper.renderModal(data);
            var modalEl = $(modalHtml);
            var bodyWrapper = $('body');
            $('.modal').remove();
            bodyWrapper.append(modalEl);
            modalEl.modal();
        },
    };

});