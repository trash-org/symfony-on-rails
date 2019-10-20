<?php

namespace App\Rails\Domain\Eloquent\Base;

use App\Rails\Domain\Db\Connection;
use Illuminate\Database\Migrations\Migration;

abstract class BaseMigrate extends Migration
{

    public $connectionName = 'default';
    public $tableName;
    protected $schema;

    public function connectionName()
    {
        return $this->connectionName;
    }

    public function tableName()
    {
        return $this->tableName;
    }

    public function __construct()
    {
        $this->schema = Connection::getSchema($this->connectionName());
    }

}