<?php

namespace common\models\orders;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $address
 * @property int|null $phone
 * @property string|null $email
 * @property string|null $date
 * @property int|null $number_order
 * @property string|null $order
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'number_order'], 'integer'],
            [['date', 'order'], 'safe'],
            [['address', 'email', 'phone'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'date' => 'Date',
            'number_order' => 'Number Order',
            'order' => 'Order',
        ];
    }
}
