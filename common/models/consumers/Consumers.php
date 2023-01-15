<?php

namespace common\models\consumers;

use Yii;

/**
 * This is the model class for table "consumers".
 *
 * @property int $id
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $email
 */
class Consumers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consumers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'email' => 'Email',
        ];
    }
}
