<?php

use yii\db\Migration;

/**
 * Class m220215_095008_create_list_country
 */
class m220215_095008_create_list_country extends Migration
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

        $this->createTable('{{%list_country}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(100),
            'name' => $this->string(50),
            'popular' => $this->integer(1),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%list_country}}');
    }
}
