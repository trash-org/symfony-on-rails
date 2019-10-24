<?php

namespace App\Rails\Eloquent\Migration\Base;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Migrations\Migration;

abstract class BaseMigration extends Migration
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
        $this->schema = Manager::schema($this->connectionName());
    }

}