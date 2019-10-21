<?php

namespace App\Bundle\Article\Domain\Migration;

use App\Rails\Eloquent\Migrate\BaseCreateTableMigrate;
use Illuminate\Database\Schema\Blueprint;

class m_2014_10_12_200000_create_post_table extends BaseCreateTableMigrate
{

    public function tableName()
    {
        return 'article_post';
    }

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title');
        };
    }

}
