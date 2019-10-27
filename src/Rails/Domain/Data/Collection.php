<?php

namespace App\Rails\Domain\Data;

use php7extension\yii\helpers\ArrayHelper;

class Collection extends \Illuminate\Database\Eloquent\Collection
{

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->map(function ($value) {
            return is_object($value) ? ArrayHelper::toArray($value) : $value;
        })->all();
    }

}