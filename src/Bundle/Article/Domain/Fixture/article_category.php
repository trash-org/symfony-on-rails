<?php

use App\Rails\Eloquent\Fixture\Helper\FixtureFactoryHelper;

$callback = function($index) {
    return [
        'id' => $index,
        'title' => 'category ' . $index,
    ];
};

return FixtureFactoryHelper::createCollection($callback, 30);
