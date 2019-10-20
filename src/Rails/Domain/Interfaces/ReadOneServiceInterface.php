<?php

namespace App\Rails\Domain\Interfaces;

use php7rails\domain\data\Query;

interface ReadOneServiceInterface
{

    public function oneById($id, Query $query = null);

}