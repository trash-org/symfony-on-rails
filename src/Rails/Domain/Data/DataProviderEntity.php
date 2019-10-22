<?php

namespace App\Rails\Domain\Data;

use php7rails\domain\BaseEntity;

/**
 * Class DataProviderEntity
 * @package App\Rails\Domain\Data
 *
 * @property $page
 * @property $pageSize
 * @property $maxPageSize
 * @property $totalCount
 * @property $pageCount
 * @property $collection
 */
class DataProviderEntity extends BaseEntity
{

    protected $page;
    protected $pageSize;
    protected $maxPageSize = 50;
    protected $totalCount;
    protected $pageCount;
    protected $collection;

    public function setPage(int $page)
    {
        $this->page = $page < 1 ? 1 : $page;
    }

    public function getPage() : int
    {
        $pageCount = $this->getPageCount();
        if($pageCount !== null && $this->page > $pageCount) {
            $this->page = $pageCount;
        }
        if($this->page < 1) {
            $this->page = 1;
        }
        return $this->page;
    }

    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize < 1 ? 1 : $pageSize;
    }

    public function getPageSize() : int
    {
        if($this->pageSize > $this->maxPageSize) {
            $this->pageSize = $this->maxPageSize;
        }
        return $this->pageSize;
    }

    public function getPageCount() : int {
        if(!isset($this->totalCount)) {
            return null;
        }
        $totalCount = $this->totalCount;

        $this->pageCount = intval(ceil($totalCount / $this->getPageSize()));

        if($this->pageCount < 1) {
            $this->pageCount = 1;
        }

        return $this->pageCount;
    }

}