<?php

namespace App\Rails\Domain\Helper\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Entity\relation\RelationEntity;
use App\Rails\Domain\Interfaces\RelationConfigInterface;
use Yii;
use App\Rails\Domain\Strategies\join\JoinStrategy;
use php7rails\domain\data\EntityCollection;
use php7rails\domain\data\Query;
use php7rails\domain\dto\WithDto;
use php7rails\domain\exceptions\BadQueryHttpException;
use php7rails\domain\helpers\DomainHelper;
use php7rails\domain\helpers\Helper;

class RelationHelper {

    public static function load(RelationConfigInterface $repository, Query $query, $data, WithDto $withDto = null) {
        $relations = $repository->relations();
        $relations = Helper::forgeEntity($relations, RelationEntity::class, true, true);

        /*if($relations) {
            dd($relations);
        }*/

        //$relations = RelationConfigHelper::getRelationsConfig($domain, $repositoryId);
        $remainOfWith = [];
        $withParams = RelationWithHelper::fetch($query, $remainOfWith);

        foreach($withParams as $relationName) {
            $newWithDto = self::forgeNewWithDto($relationName, $relations);
            $newWithDto->withParams = $withParams;
            $newWithDto->remain = $remainOfWith;
            self::hh($withDto, $newWithDto);
            self::prepareWithDto($query, $newWithDto);
            $data = empty($data) ? [] : $data;
            $data = self::loadRelations($data, $newWithDto);
        }

        return $data;
    }

    /*public static function load222($repository, Query $query, $data, WithDto $withDto = null) {
        //string $domain, string $repositoryId
        $relations = RelationConfigHelper::getRelationsConfig22($repository);
        $remainOfWith = [];
        $withParams = RelationWithHelper::fetch($query, $remainOfWith);
        foreach($withParams as $relationName) {
            $newWithDto = self::forgeNewWithDto($relationName, $relations);
            $newWithDto->withParams = $withParams;
            $newWithDto->remain = $remainOfWith;
            self::hh($withDto, $newWithDto);
            self::prepareWithDto($query, $newWithDto);
            $data = empty($data) ? [] : $data;
            $data = self::loadRelations($data, $newWithDto);
        }
        return $data;
    }

	public static function load($repository, Query $query, $data, WithDto $withDto = null) {
		//$relations = RelationConfigHelper::getRelationsConfig($domain, $repositoryId);
        $relations = RelationConfigHelper::getRelationsConfig22($repository);
		$remainOfWith = [];
		$withParams = RelationWithHelper::fetch($query, $remainOfWith);
		foreach($withParams as $relationName) {
			$newWithDto = self::forgeNewWithDto($relationName, $relations);
			$newWithDto->withParams = $withParams;
			$newWithDto->remain = $remainOfWith;
			self::hh($withDto, $newWithDto);
			self::prepareWithDto($query, $newWithDto);
			$data = empty($data) ? [] : $data;
			$data = self::loadRelations($data, $newWithDto);
		}
		return $data;
	}*/
	
	private static function hh($withDto, WithDto $newWithDto) : void {
		if($withDto instanceof WithDto) {
			$newWithDto->passed = trim($withDto->passed . DOT . $newWithDto->relationName, DOT);
		} else {
			$newWithDto->passed = $newWithDto->relationName;
		}
	}
	
	private static function forgeNewWithDto(string $relationName, array $relations) : WithDto {
		if(!array_key_exists($relationName, $relations)) {
			throw new BadQueryHttpException(\Rails::t('domain/db', 'relation_not_defined {field}', ['field' => $relationName]));
		}
		$w = new WithDto();
		$w->relationConfig = $relations[$relationName];
		$w->relationName = $relationName;
		return $w;
		/*if(strpos($w->passed, DOT) !== false) {
			if($query instanceof Query && $query->getNestedQuery($w->passed) instanceof Query) {
				print_r($query->getNestedQuery($w->passed)->toArray());exit;
			}
		}*/
	}
	
	private static function prepareWithDto(Query $query, WithDto $withDto) : void {
		if($query->getNestedQuery($withDto->passed) instanceof Query) {
			$withDto->query = $query->getNestedQuery($withDto->passed);
			$withDto->query->with($withDto->remain[$withDto->relationName]);
		} else {
			$withDto->query = clone $query;
			$withDto->query->removeParam('with');
			$withDto->query->with($withDto->remain[$withDto->relationName]);
		}
	}
	
	private static function loadRelations($data, WithDto $w) {
		$isEntity = DomainHelper::isEntity($data);
        /** @var Collection $collection */
        $collection = $isEntity ? [$data] : $data;
		$collection = self::loadRelationsForCollection($collection, $w);
		return $isEntity ? $collection[0] : $collection;
	}
	
	private static function loadRelationsForCollection(Collection $collection, WithDto $withDto) : Collection {
		/** @var EntityCollection $relCollection */
		
		$joinStrategy = new JoinStrategy();
		$joinStrategy->setStrategyName($withDto->relationConfig->type);
		$relCollection = $joinStrategy->join($collection, $withDto->relationConfig);
		if(!empty($relCollection)) {
			foreach($collection as &$entity) {
				$relationEntity = $joinStrategy->load($entity, $withDto, $relCollection);
				if(!empty($withDto->remain[$withDto->relationName])) {
					self::load($relationEntity->foreign->model, $withDto->query, $entity->{$withDto->relationName}, $withDto);
				}
			}
		}
		return $collection;
	}
	
}
