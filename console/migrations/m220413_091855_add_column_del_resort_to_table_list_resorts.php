<?php

use yii\db\Migration;

/**
 * Class m220413_091855_add_column_del_resort_to_table_list_resorts
 */
class m220413_091855_add_column_del_resort_to_table_list_resorts extends Migration
{
    public function up()
    {
        $this->addColumn('{{list_resorts}}', 'del_resort', $this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{list_resorts}}', 'del_resort');
    }
}
