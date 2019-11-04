<?php

namespace App\Rails\Eloquent\Fixture\Helper;

class FixtureFactoryHelper
{

    public static function ord($index, $count) {
        $categoryId = ($index + $count - 1) % $count + 1;
        return $categoryId;
    }

    public static function createCollection($callback, $count, $startIndex = 1, $step = 1) {
        $collection = [];
        for ($i = $startIndex; $i <= $count; $i = $i + $step) {
            $collection[] = $callback($i);
        }
        return $collection;
    }

}