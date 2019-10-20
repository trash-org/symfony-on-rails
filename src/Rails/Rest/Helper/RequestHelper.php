<?php

namespace App\Rails\Rest\Helper;

use php7rails\domain\data\Query;
use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{

    public static function forgeQueryFromRequest(Request $request, Query $query = null) : Query {
        $query = Query::forge($query);
        $queryParams = $request->query->all();
        foreach ($queryParams as $paramName => $paramValue) {
            $query->andWhere([$paramName => $paramValue]);
        }
        return $query;
    }

}