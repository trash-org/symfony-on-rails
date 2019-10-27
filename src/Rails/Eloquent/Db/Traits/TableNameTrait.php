<?php

namespace App\Rails\Eloquent\Db\Traits;

use App\Rails\Eloquent\Db\Helper\TableAliasHelper;

trait TableNameTrait
{

    protected $connectionName = 'default';
    protected $tableName;

    public function connectionName()
    {
        return $this->connectionName;
    }

    public function tableName()
    {
        return $this->encodeTableName($this->tableName);
    }

    public function encodeTableName(string $sourceTableName) : string
    {
        $targetTableName = TableAliasHelper::encode($this->connectionName(), $sourceTableName);
        return $targetTableName;
    }

}