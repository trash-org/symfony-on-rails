<?php

namespace App\Rails\Domain\Interfaces;

use php7rails\domain\data\Query;

interface ReadAllServiceInterface
{

    public function all(Query $query = null);

    public function count(Query $query = null) : int;

}