<?php

use yii\db\Migration;

/**
 * Class m220120_075611_add_user_id_to_table_customers
 */
class m220120_075611_add_user_id_to_table_customers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{customers}}', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{customer}}', 'user_id');
    }
}
