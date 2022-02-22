<?php

use yii\db\Migration;

/**
 * Class m220222_130049_add_column_rating_to_list_resorts
 */
class m220222_130049_add_column_rating_to_list_resorts extends Migration
{
    public function up()
    {
        $this->addColumn('{{list_resorts}}', 'rating', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{list_resorts}}', 'rating');
    }
}
