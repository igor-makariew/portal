<?php

use yii\db\Migration;

/**
 * Class m220411_072321_add_column_role_to_table_user
 */
class m220411_072321_add_column_role_to_table_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{user}}', 'role', $this->string(32));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{user}}', 'role');
    }
}
