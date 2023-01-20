<?php

use yii\db\Migration;

/**
 * Class m230119_082900_create_table_post_auto
 */
class m230119_082900_create_table_post_auto extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post_auto}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128),
            'post' => $this->text(),
            'author' => $this->string(64),
            'image' => $this->string(64),
            'date' => $this->date(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%post_auto}}');
    }
}
