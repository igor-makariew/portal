<?php

use yii\db\Migration;

/**
 * Class m221108_162925_create_table_chat
 */
class m221108_162925_create_table_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%chat}}', [
            'id' => $this->primaryKey(),
            'id_from' => $this->integer(),
            'name_from' => $this->string(50),
            'message_from' => $this->text(),
            'id_to' => $this->integer(),
            'name_to' => $this->string(50),
            'date' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%chat}}');
    }
}
