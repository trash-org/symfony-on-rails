<?php

namespace App\Rails\Domain\Strategies\join;

use php7rails\domain\BaseEntity;
use php7rails\domain\dto\WithDto;
use App\Rails\Domain\Entity\relation\RelationEntity;
use php7rails\domain\enums\RelationEnum;
use php7extension\core\scenario\base\BaseStrategyContextHandlers;
use App\Rails\Domain\Strategies\join\handlers\One;
use App\Rails\Domain\Strategies\join\handlers\Many;
use App\Rails\Domain\Strategies\join\handlers\ManyToMany;
use App\Rails\Domain\Strategies\join\handlers\HandlerInterface;

/**
 * Class PaymentStrategy
 *
 * @package App\Rails\Domain\Strategies\payment
 *
 * @property-read HandlerInterface $strategyInstance
 */
class JoinStrategy extends BaseStrategyContextHandlers {
	
	public function getStrategyDefinitions() {
		return [
			RelationEnum::ONE => One::class,
			RelationEnum::MANY => Many::class,
			RelationEnum::MANY_TO_MANY => ManyToMany::class,
		];
	}
	
	public function load($entity, WithDto $w, $relCollection) : RelationEntity {
		return $this->strategyInstance->load($entity, $w, $relCollection);
	}
	
	public function join($collection, RelationEntity $relationEntity) {
		if(empty($collection)) {
			return null;
		}
		return $this->strategyInstance->join($collection, $relationEntity);
	}
	
}