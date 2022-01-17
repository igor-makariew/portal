<?php

namespace common\models\customers;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $title
 * @property string|null $hotel
 * @property int|null $raiting
 * @property string|null $price
 * @property string|null $date
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['raiting'], 'integer'],
            [['date'], 'safe'],
            [['name', 'email', 'title', 'hotel', 'price'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'title' => 'Title',
            'hotel' => 'Hotel',
            'raiting' => 'Raiting',
            'price' => 'Price',
            'date' => 'Date',
        ];
    }
}
