<?php

namespace App\Rails\Domain\Strategies\join\handlers;

use php7rails\domain\BaseEntity;
use php7rails\domain\data\Query;
use php7rails\domain\dto\WithDto;
use App\Rails\Domain\Entity\relation\RelationEntity;
use App\Rails\Domain\Helper\Repository\RelationRepositoryHelper;
use php7extension\core\arrayTools\helpers\ArrayIterator;

class Many extends Base implements HandlerInterface {
	
	public function join( $collection, RelationEntity $relationEntity) {
		$values = self::getColumn($collection, $relationEntity->field);

		$query = Query::forge();
		$query->where($relationEntity->foreign->field, $values);

		$relCollection = RelationRepositoryHelper::getAll($relationEntity->foreign, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
		$fieldValue = $entity->{$w->relationConfig->field};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		$query = Query::forge();
		$query->where($w->relationConfig->foreign->field, $fieldValue);
		$data = ArrayIterator::allFromArray($query, $relCollection);
		$data = self::prepareValue($data, $w);
		$entity->{$w->relationName} = $data;
		return $w->relationConfig;
	}
	
}