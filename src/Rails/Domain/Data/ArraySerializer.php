<?php

namespace App\Rails\Domain\Data;

use App\Rails\Domain\Data\ArraySerializerHandlers\ArrayHandler;
use App\Rails\Domain\Data\ArraySerializerHandlers\ObjectHandler;
use App\Rails\Domain\Data\ArraySerializerHandlers\TimeHandler;
use php7extension\yii\helpers\ArrayHelper;

class ArraySerializer
{

    public $properties = [];
    public $recursive = true;
    public $handlers = [
        ArrayHandler::class,
        //TimeHandler::class,
        ObjectHandler::class,
    ];
    protected $handlerInstances = [];

    public function setHandlerClasses(array $handlers)
    {
        foreach ($handlers as $handler) {
            $this->setHandlerClass($handler);
        }
    }

    public function setHandlerClass($handler)
    {
        $instance = new $handler;
        $instance->properties = $this->properties;
        $instance->recursive = $this->recursive;
        $instance->parent = $this;
        $this->handlerInstances[] = $instance;
        //return $instance;
    }

    public function __construct($handlers = null)
    {
        $handlers = $handlers ?? $this->handlers;
        $this->setHandlerClasses($handlers);
    }

    public function toArray($object)
    {
        foreach ($this->handlerInstances as $handler) {
            $object = $handler->encode($object);
        }
        return $object;
    }

    /*protected function arrayHandle(array $object) : array {
        if ($this->recursive) {
            foreach ($object as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $object[$key] = $this->toArray($value);
                }
            }
        }
        return $object;
    }*/

    /*protected function objectHandle(array $object) : array {
        if (!empty($this->properties)) {
            $className = get_class($object);
            if (!empty($this->properties[$className])) {
                $result = [];
                foreach ($this->properties[$className] as $key => $name) {
                    if (is_int($key)) {
                        $result[$name] = $object->$name;
                    } else {
                        $result[$key] = ArrayHelper::getValue($object, $name);
                    }
                }

                return $this->recursive ? $this->toArray($result) : $result;
            }
        }
        if (method_exists($object, 'toArray')) { // if ($object instanceof Arrayable) {
            $result = $object->toArray([], []);
        } else {
            $result = [];
            foreach ($object as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $this->recursive ? $this->toArray($result) : $result;
    }*/

}