<?php

namespace App\Rails\Eloquent\Migration\Base;

use App\Rails\Eloquent\Db\Traits\TableNameTrait;
use Illuminate\Database\Schema\Builder;

abstract class BaseMigration
{

    use TableNameTrait;

    protected function runSqlQuery(Builder $schema, $sql) {
        $connection = $schema->getConnection();
        $rawSql = $connection->raw($sql);
        $connection->select($rawSql);
    }

}