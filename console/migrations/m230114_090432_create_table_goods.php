<?php

use yii\db\Migration;

/**
 * Class m230114_090432_create_table_goods
 */
class m230114_090432_create_table_goods extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%goods}}', [
            'id' => $this->primaryKey(),
            'price' => $this->integer(),
            'title' => $this->string(64),
            'number' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%goods}}');
    }
}
