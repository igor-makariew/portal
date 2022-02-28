<?php

use yii\db\Migration;

/**
 * Class m220228_055710_add_column_name_to_table_comments
 */
class m220228_055710_add_column_name_to_table_comments extends Migration
{
    public function up()
    {
        $this->addColumn('{{comments}}', 'name', $this->string(64));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{comments}}', 'name');
    }
}
