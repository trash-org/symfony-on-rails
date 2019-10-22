<?php

namespace App\Rails\Eloquent\Migration\Base;

use App\Rails\Eloquent\Db\Helper\Connection;
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