<?php

use yii\db\Migration;

/**
 * Class m221207_070310_add_mailing_list_to_table_user
 */
class m221207_070310_add_mailing_list_to_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{user}}', 'mailing_list', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{user}}', 'mailing_list');
    }
}
