<?php

namespace App\Rails\Domain\Helper\Repository;

use App\Rails\Domain\Entity\relation\RelationEntity;
use App\Rails\Domain\Interfaces\RelationConfigInterface;
use php7rails\domain\helpers\Helper;

class RelationConfigHelper {

    public static function getRelationsConfig22(RelationConfigInterface $repository) : array {
        $relations = $repository->relations();
        $relations = Helper::forgeEntity($relations, RelationEntity::class, true, true);
        return $relations;
    }

    /**
     * @param RelationConfigInterface $repository
     * @return RelationEntity[]
     */
	public static function getRelationsConfig(RelationConfigInterface $repository) : array {
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		$relationsCollection = Helper::forgeEntity($relations, RelationEntity::class, true, true);
		return $relationsCollection;
	}
	
	private static function normalizeConfig(array $relations) : array {
		foreach($relations as &$relation) {
			if(!empty($relation['via']['this'])) {
				$relation['via']['self'] = $relation['via']['this'];
				unset($relation['via']['this']);
			}
		}
		return $relations;
	}
	
}
