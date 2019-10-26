<?php

namespace App\Rails\Eloquent\Db\Helper;

use App\Rails\Domain\Interfaces\ReadAllServiceInterface;
use php7rails\domain\data\Query;
use php7rails\domain\helpers\repository\RelationHelper;
use php7rails\domain\helpers\repository\RelationWithHelper;
use php7rails\domain\repositories\BaseRepository;

/**
 * Class QueryFilter
 *
 * @package php7rails\domain\helpers\repository
 *
 */
class QueryFilter {
	
	/**
	 * @var BaseRepository
	 */
	private $repository;
	private $query;
	private $with;

	public function __construct(ReadAllServiceInterface $repository, Query $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    public function getQueryWithoutRelations() : Query {
		$query = clone $this->query;
		$this->with = RelationWithHelper::cleanWith($this->repository->relations(), $query);
		return $query;
	}
	
	public function loadRelations($data) {
		if(empty($this->with)) {
			return $data;
		}
		$collection = RelationHelper::load($this->repository, $this->query, $data);
		//dd($collection);
		return $collection;
	}
	
	/*public function getQuery() : Query {
		if(!isset($this->query)) {
			$this->query = Query::forge();
		}
		return $this->query;
	}
	
	public function setQuery(Query $query) {
		$this->query = clone $query;
	}*/
	
}
