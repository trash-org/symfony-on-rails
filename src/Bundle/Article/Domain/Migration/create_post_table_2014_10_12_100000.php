<?php

namespace App\Bundle\Article\Domain\Migration;

use App\Rails\Domain\Eloquent\Base\BaseCreateTableMigrate;
use Illuminate\Database\Schema\Blueprint;

class create_post_table_2014_10_12_100000 extends BaseCreateTableMigrate
{

    public function tableName()
    {
        return 'post22222';
    }

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title');
        };
    }

}
