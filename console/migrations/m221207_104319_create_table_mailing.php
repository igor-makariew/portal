<?php

use yii\db\Migration;

/**
 * Class m221207_104319_create_table_mailing
 */
class m221207_104319_create_table_mailing extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mailing}}', [
            'id' => $this->primaryKey(),
            'id_user_mailing' => $this->integer()->notNull(),
            'subject' => $this->string(64),
            'textbody' => $this->text(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%mailing}}');
    }
}
