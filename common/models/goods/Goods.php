<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property int|null $price
 * @property string|null $title
 * @property int|null $number
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'number'], 'integer'],
            [['title'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'title' => 'Title',
            'number' => 'Number',
        ];
    }
}
