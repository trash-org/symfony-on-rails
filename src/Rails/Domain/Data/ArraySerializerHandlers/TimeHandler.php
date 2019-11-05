<?php

namespace App\Rails\Domain\Data\ArraySerializerHandlers;

use App\Rails\Domain\Data\ArraySerializer;
use DateTime;

class TimeHandler
{

    public $properties = [];
    public $recursive = true;

    /** @var ArraySerializer */
    public $parent;

    public function encode($object) {
        if($object instanceof DateTime) {
            $object = $this->objectHandle($object);
        }
        return $object;
    }

    protected function objectHandle(DateTime $object) : string {
        return $object->format('c');
    }
}