<?php

use yii\db\Migration;

/**
 * Class m220224_063153_create_table_rating
 */
class m220224_063153_create_table_rating extends Migration
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

        $this->createTable('{{%rating}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'rating' => $this->float()->defaultValue(0),
            'resorts_id' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%rating}}');
    }
}
