<?php

use yii\db\Migration;

/**
 * Class m221207_114020_add_columns_email_username_phone_in_table_mailing
 */
class m221207_114020_add_columns_email_username_phone_in_table_mailing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{mailing}}', 'username', $this->string(32));
        $this->addColumn('{{mailing}}', 'email', $this->string(64));
        $this->addColumn('{{mailing}}', 'phone', $this->string(16));
        $this->addColumn('{{mailing}}', 'sent', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{mailing}}', 'username');
        $this->dropColumn('{{mailing}}', 'email');
        $this->dropColumn('{{mailing}}', 'phone');
        $this->dropColumn('{{mailing}}', 'sent');
    }
}
