space('bundle.module.todo.store.contactStore', function() {

    var localStore = bundle.helper.class.extends(bundle.domain.baseLocalStorage, {

        storageKey: 'todo3',

    });

    return {

        collection: undefined,

        deleteById: function (id) {
            this._forge();
            var entity = this.oneById(id);
            var index = this.collection.indexOf(entity);
            if (index !== -1) {
                this.collection.splice(index, 1);
                this._saveToLocal();
            }
        },
        delete: function (entity) {
            this._forge();
            var index = this.collection.indexOf(entity);
            if (index !== -1) {
                this.collection.splice(index, 1);
                this._saveToLocal();
            }
        },
        create: function (contactEntity) {
            this._forge();
            if(_.isEmpty(contactEntity.id)) {
                var lastEntity = _.maxBy(this.collection, 'id');
                if(lastEntity) {
                    contactEntity.id = lastEntity.id + 1;
                } else {
                    contactEntity.id = 1;
                }
            }
            this.collection.push(contactEntity);
            this._saveToLocal();
        },
        update: function (contactEntity) {
            this._forge();
            var entity = this.oneById(contactEntity.id);
            var index = this.collection.indexOf(entity);
            entity = _.assign(entity, contactEntity);
            this.collection[index] = entity;
            this._saveToLocal();
        },
        one: function (query) {
            this._forge();
            return _.find(this.collection, query);
        },
        all: function (query) {
            this._forge();
            if(query) {
                return _.filter(this.collection, query);
            } else {
                return this.collection;
            }
        },
        oneById: function (id) {
            this._forge();
            id = _.toInteger(id);
            return _.find(this.collection, { 'id': id });
        },
        _forge: function () {
            if(this.collection === undefined) {
                this.collection = localStore.get([]);
            }
        },
        _saveToLocal: function () {
            var value = _.defaultTo(this.collection, []);
            localStore.set(value);
        }
    };

});
