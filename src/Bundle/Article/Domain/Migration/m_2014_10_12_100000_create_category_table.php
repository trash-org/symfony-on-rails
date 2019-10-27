<?php

namespace App\Bundle\Article\Domain\Migration;

use App\Rails\Eloquent\Migration\Base\BaseCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;

class m_2014_10_12_100000_create_category_table extends BaseCreateTableMigration
{

    protected $tableName = 'article_category';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title');
        };
    }

}
