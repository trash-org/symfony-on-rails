<?php

namespace App\Rails\Domain\Strategies\join\handlers;

use php7rails\domain\BaseEntity;
use php7rails\domain\dto\WithDto;
use App\Rails\Domain\Entity\relation\RelationEntity;

interface HandlerInterface {
	
	public function join($collection, RelationEntity $relationEntity);
	public function load(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity;
	
}
