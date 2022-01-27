<?php

namespace common\models\customers;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
 * @property int|null $user_id
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
            [['raiting', 'user_id'], 'integer'],
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
            'user_id' => 'User Id'
        ];
    }

    /**
     * Метод расширяет возможности класса Customers, внедряя дополительные
     * свойства и методы. Кроме того, позволяет реагировать на события,
     * создаваемые классом Order или его родителями
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // при вставке новой записи присвоить атрибутам created
                    // и updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'], //,'updated'],
                    // при обновлении существующей записи  присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
                // если вместо метки времени UNIX используется DATETIME
                'value' => new Expression('NOW()'),
            ],

        ];
    }
}
