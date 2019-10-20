<?php

namespace App\Rails\Domain\Helper;

use php7extension\yii\helpers\Inflector;

class EntityHelper
{

    public static function toArray($entity) {

        $attributes = self::getAttributes($entity);
        $array = [];
        foreach ($attributes as $attribute => $method) {
            $array[$attribute] = $entity->{$method};
        }
        return $array;
    }

    public static function getAttributes($entity) {
        $methods = get_class_methods($entity);
        //dd($methods);
        $arr = [];
        foreach ($methods as $method) {
            $method1 = substr($method, 3);
            $method1 = Inflector::camel2id($method1);
            $arr[$method1] = $method;
        }
        $arr = array_unique($arr);
        return $arr;
    }

    public static function createEntityCollection($entityClass,  $data = []) {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = self::createEntity($entityClass, $item);
        }
        return $collection;
    }

    public static function createEntity($entityClass,  $data = []) {
        $entity = new $entityClass;
        self::setAttributes($entity, $data);
        return $entity;
    }

    public static function setAttributes($class,  $data = []) {
        foreach ($data as $attrName => $attrValue) {
            $class->{$attrName} = $attrValue;
        }
    }

}