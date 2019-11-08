<?php

namespace Migrations;

use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;

if( ! class_exists(m_2014_10_12_100000_create_category_table::class)) {

    class m_2014_10_12_100000_create_category_table extends BaseCreateTableMigration
    {

        protected $tableName = 'article_category';
        protected $tableComment = 'Категории статей';

        public function tableSchema()
        {
            return function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('title')->comment('Название категории');
            };
        }

    }

}