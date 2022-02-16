<?php

use yii\db\Migration;

/**
 * Class m220215_112000_create_list_resorts
 */
class m220215_112000_create_list_resorts extends Migration
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

        $this->createTable('{{%list_resorts}}', [
            'id' => $this->primaryKey(),
            'resorts_id' => $this->integer(11)->notNull(),
            'name' => $this->string(50)->notNull(),
            'is_popular' => $this->boolean(),
            'resort_country_id' => $this->integer(10)->notNull(),
            'at_filtering' => $this->boolean(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%list_resorts}}');
    }
}
