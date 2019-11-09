<?php

use PhpLab\Eloquent\Fixture\Helper\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(30);
$fixture->setCallback(function ($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'title' => 'category ' . $index,
    ];
});
return $fixture->generateCollection();
