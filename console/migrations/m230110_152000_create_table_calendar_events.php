<?php

use yii\db\Migration;

/**
 * Class m230110_152000_create_table_calendar_events
 */
class m230110_152000_create_table_calendar_events extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%calendar_events}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text(),
            'event' => $this->string(64),
            'created_at' => $this->date(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%calendar_events}}');
    }
}
