<?php

namespace App\Rails\Domain\Entity\relation;

/**
 * Class ForeignEntity
 *
 * @package App\Rails\Domain\Entity\relation
 *
 * @property $field
 * @property $value
 */
class ForeignEntity extends BaseForeignEntity {
	
	protected $field = 'id';
	protected $value;
	
}