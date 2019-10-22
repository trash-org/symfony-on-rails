<?php

namespace App\Rails\Eloquent\Migration\Base;

abstract class BaseCreateTableMigrate extends BaseMigrate
{

    abstract public function tableSchema();

    public function up()
    {
        $this->schema->create($this->tableName(), $this->tableSchema());
    }

    public function down()
    {
        $this->schema->dropIfExists($this->tableName());
    }

}