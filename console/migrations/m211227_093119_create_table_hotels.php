<?php

use yii\db\Migration;

/**
 * Class m211227_093119_create_table_hotels
 */
class m211227_093119_create_table_hotels extends Migration
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

        $this->createTable('{{%hotels}}', [
            'id' => $this->primaryKey(),
            'location_name' => $this->string(),
            'label' => $this->string(),
            'location' => $this->string(),
            'full_name' => $this->string(),
            'hotel_id' => $this->string(),
            'location_id' => $this->string(),
            'hotel_name' => $this->string(),
            'price_form' => $this->string(),
            'price_percentile' => $this->string(),
            'stars' => $this->string(),
            'price_avg' => $this->string(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%hotels}}');
    }
}
