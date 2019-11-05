<?php

use App\Rails\Eloquent\Fixture\Helper\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(200);
$fixture->setCallback(function($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'title' => 'post ' . $index,
        'category_id' => $fixtureFactory->ordIndex($index, 3),
    ];
});
return $fixture->generateCollection();
