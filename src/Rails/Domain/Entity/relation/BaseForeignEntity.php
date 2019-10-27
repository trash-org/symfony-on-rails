<?php

namespace App\Rails\Domain\Entity\relation;

use php7rails\domain\BaseEntity;
use php7rails\domain\enums\RelationClassTypeEnum;

/**
 * Class BaseForeignEntity
 *
 * @package App\Rails\Domain\Entity\relation
 *
 * @property $id
 * @property $domain
 * @property $name
 * @property $model
 * @property $classType
 */
class BaseForeignEntity extends BaseEntity {
	
	protected $id;
	protected $domain;
	protected $name;
    protected $model;
	protected $classType = RelationClassTypeEnum::REPOSITORY;
	
	public function rules() {
		return [
			[['classType'], 'in', 'range' => RelationClassTypeEnum::values()],
		];
	}
	
	public function setId($id) {
		list($this->domain, $this->name) = explode(DOT, $id);
	}
	
	public function getId() {
	
	}
	
}