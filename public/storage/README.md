# Контент

Тут лежат ресурсы для контента.
Пользователь, со стороны WEB, имеет право заливать сюда файлы.

Для каждой предметной_области/сущности создается вложенность папок.
Например, сущность вложений для сообщений будет расположена так:

    /public/storage/message/123/123456_A4F18C22.png

Аватар пользователя можно хранить так:

    /public/storage/user/123/thumb32x32/123456_A4F18C22.png
    /public/storage/user/123/thumb256x256/123456_A4F18C22.png

* `/123/` - группировка по секциям ID. Необходимо для уменьшения нагрузки на файловую таблицу.
* `/thumb256x256/` - размер эскиза (миниатюры) изображений
* `/123456_` - ID пользователя
* `_A4F18C22.` - хэш содержимого картинки. Решает проблему кэширования и именной коллизии.
