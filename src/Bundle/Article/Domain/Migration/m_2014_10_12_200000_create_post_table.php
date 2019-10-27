<?php

namespace App\Bundle\Article\Domain\Migration;

use App\Rails\Eloquent\Migration\Base\BaseCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;

class m_2014_10_12_200000_create_post_table extends BaseCreateTableMigration
{

    protected $tableName = 'article_post';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('category_id');
            $table->string('title');
            $table
                ->foreign('category_id')
                ->references('id')
                ->on($this->encodeTableName('article_category'))
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        };
    }

}
