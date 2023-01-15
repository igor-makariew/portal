<?php

use yii\db\Migration;

/**
 * Class m230114_111208_create_table_orders
 */
class m230114_111208_create_table_orders extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'address' => $this->string(64),
            'phone' => $this->integer(),
            'email' => $this->string(64),
            'date' => $this->date(),
            'number_order' => $this->integer(),
            'order' => $this->json(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%orders}}');
    }
}
