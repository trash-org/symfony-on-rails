<?php

namespace App\Rails\Domain\Helper\Repository;

use App\Rails\Domain\Data\Collection;
use php7extension\yii\base\BaseObject;
use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\data\Query;
use App\Rails\Domain\Entity\relation\BaseForeignEntity;
use php7rails\domain\enums\RelationClassTypeEnum;
use php7rails\domain\interfaces\services\ReadAllInterface;

class RelationRepositoryHelper {
	
	public static function getAll(BaseForeignEntity $relationConfig, Query $query = null) : Collection {
		$query = Query::forge($query);
		/** @var ReadAllInterface $repository */
        $repository = $relationConfig->model;
        //dd($query);
		//$repository = self::getInstance($relationConfig);
		return $repository->all($query);
	}
	
	private static function getInstance(BaseForeignEntity $relationConfigForeign) : object {
	    $domainInstance = \App::$domain->get($relationConfigForeign->domain);
		if($relationConfigForeign->classType == RelationClassTypeEnum::SERVICE) {
			$locator = $domainInstance;
		} else {
			$locator = $domainInstance->repositories;
		}
		return ArrayHelper::getValue($locator, $relationConfigForeign->name);
	}
	
}
