<?php

use App\Rails\Eloquent\Fixture\Helper\FixtureFactoryHelper;

$callback = function($index) {
    return [
        'id' => $index,
        'title' => 'post ' . $index,
        'category_id' => FixtureFactoryHelper::ord($index, 3),
    ];
};

return FixtureFactoryHelper::createCollection($callback, 900);
