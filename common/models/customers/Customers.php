<?php

namespace common\models\customers;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
class Customers extends ActiveRecord
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

    public function behaviors()
    {
        return [
            //Использование поведения TimestampBehavior ActiveRecord
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,

                ],
                'value' => new \yii\db\Expression('NOW()'),
//                'value' => function(){
//                    return gmdate("Y-m-d H:i:s");
//                },


            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'title' => 'Название',
            'hotel' => 'Hotel',
            'raiting' => 'Рейтинг',
            'price' => 'Цена',
            'date' => 'Дата',
        ];
    }
}
