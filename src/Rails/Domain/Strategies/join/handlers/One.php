<?php

namespace App\Rails\Domain\Strategies\join\handlers;

use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\BaseEntity;
use php7rails\domain\dto\WithDto;
use App\Rails\Domain\Entity\relation\RelationEntity;

class One extends Many implements HandlerInterface {
	
	public function join($collection, RelationEntity $relationEntity) {
		$relCollection = parent::join($collection, $relationEntity);
		$relCollection = ArrayHelper::index($relCollection, $relationEntity->foreign->field);
		return $relCollection;
	}
	
	public function load($entity, WithDto $w, $relCollection): RelationEntity {
		$fieldValue = ArrayHelper::getValue($entity, $w->relationConfig->field);
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		if(array_key_exists($fieldValue, $relCollection)) {
			$data = $relCollection[$fieldValue];
			$data = self::prepareValue($data, $w);
            $entity->{$w->relationName} = $data;
		}
		return $w->relationConfig;
	}
	
}