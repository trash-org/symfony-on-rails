<?php

namespace App\Rails\Domain\Entity\relation;

use php7rails\domain\BaseEntity;
use php7rails\domain\enums\RelationEnum;

/**
 * Class RelationEntity
 *
 * @package App\Rails\Domain\Entity\relation
 *
 * @property $type
 * @property $field
 * @property ForeignEntity $foreign
 * @property ForeignViaEntity $via
 */
class RelationEntity extends BaseEntity {
	
	protected $type;
	protected $field;
	protected $foreign;
	protected $via;
	
	public function fieldType() {
		return [
			'foreign' => ForeignEntity::class,
			'via' => ForeignViaEntity::class,
		];
	}
	
	public function rules() {
		return [
			[['type'], 'required'],
			[['type'], 'in', 'range' => RelationEnum::values()],
		];
	}
	
}
