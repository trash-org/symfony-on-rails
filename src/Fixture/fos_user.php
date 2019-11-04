<?php

use App\Rails\Eloquent\Fixture\Helper\FixtureFactoryHelper;

$callback = function($index) {
    $username = 'user' . $index;
    return [
        'id' => $index,
        'username' => $username,
        'username_canonical' => $username,
        'email' => $username . '@example.com',
        'email_canonical' => $username . '@example.com',
        'enabled' => 1,
        'password' => '$2y$13$2BjMn.uhY8Yal6kICMoN.OuOIinRKmX7ld/sCJhGd6rpUjAR9d3DG',
        'last_login' => '2019-10-27 18:11:21',
        'roles' => 'a:0:{}',
    ];
};

return FixtureFactoryHelper::createCollection($callback, 10);
