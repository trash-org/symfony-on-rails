<?php

namespace App\Rails\Eloquent\Migration\Base;

use App\Rails\Eloquent\Db\Traits\TableNameTrait;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Migrations\Migration;

abstract class BaseMigration extends Migration
{

    use TableNameTrait;

    protected $schema;

    public function __construct()
    {
        $this->schema = Manager::schema($this->connectionName());
    }

}