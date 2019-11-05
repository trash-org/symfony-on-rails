<?php

namespace App\Rails\Domain\Data\ArraySerializerHandlers;

use App\Rails\Domain\Data\ArraySerializer;

class ArrayHandler
{

    public $properties = [];
    public $recursive = true;

    /** @var ArraySerializer */
    public $parent;

    public function encode($object) {
        if(is_array($object)) {
            $object = $this->arrayHandle($object);
        }
        return $object;
    }

    protected function arrayHandle(array $object) : array {
        if ($this->recursive) {
            foreach ($object as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $object[$key] = $this->parent->toArray($value);
                }
            }
        }
        return $object;
    }
}