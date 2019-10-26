<?php

namespace App\Rails\Domain\Strategies\join\handlers;

use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\BaseEntity;
use php7rails\domain\data\Query;
use php7rails\domain\dto\WithDto;
use App\Rails\Domain\Entity\relation\RelationEntity;
use App\Rails\Domain\Helper\Repository\RelationConfigHelper;
use App\Rails\Domain\Helper\Repository\RelationRepositoryHelper;
use php7extension\core\arrayTools\helpers\ArrayIterator;

class ManyToMany extends Base implements HandlerInterface {
	
	public function join($collection, RelationEntity $relationEntity) {
		/** @var RelationEntity[] $viaRelations */
		$viaRelations = RelationConfigHelper::getRelationsConfig($relationEntity->via->domain, $relationEntity->via->name);
		$name = $relationEntity->via->self;
		$viaRelationToThis = $viaRelations[$name];
		$values = ArrayHelper::getColumn($collection, $viaRelationToThis->foreign->field);
		$query = Query::forge();
		$query->where($viaRelationToThis->field, $values);
		$relCollection = RelationRepositoryHelper::getAll($relationEntity->via, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
		$viaRelations = RelationConfigHelper::getRelationsConfig($w->relationConfig->via->domain, $w->relationConfig->via->name);
		/** @var RelationEntity $viaRelationToThis */
		$viaRelationToThis = $viaRelations[$w->relationConfig->via->self];
		/** @var RelationEntity $viaRelationToForeign */
		$viaRelationToForeign = $viaRelations[$w->relationConfig->via->foreign];
		$itemValue = $entity->{$viaRelationToForeign->foreign->field};
		$viaQuery = Query::forge();
		$viaQuery->where($viaRelationToThis->field, $itemValue);
		$viaData = ArrayIterator::allFromArray($viaQuery, $relCollection);
		$foreignIds = ArrayHelper::getColumn($viaData, $viaRelationToForeign->field);
		$query = Query::forge();
		$query->where($viaRelationToForeign->foreign->field, $foreignIds);
		$data = RelationRepositoryHelper::getAll($viaRelationToForeign->foreign, $query);
		$data = self::prepareValue($data, $w);
		$entity->{$w->relationName} = $data;
		return $viaRelationToForeign;
	}
}