<?php

namespace common\models\chat;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int|null $id_from
 * @property string|null $name_from
 * @property string|null $message_from
 * @property int|null $id_to
 * @property string|null $name_to
 * @property string|null $date
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_from', 'id_to'], 'integer'],
            [['message_from'], 'string'],
            [['date'], 'safe'],
            [['name_from', 'name_to'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_from' => 'Id From',
            'name_from' => 'Name From',
            'message_from' => 'Message From',
            'id_to' => 'Id To',
            'name_to' => 'Name To',
            'date' => 'Date',
        ];
    }

    /**
     * Метод расширяет возможности класса Customers, внедряя дополительные
     * свойства и методы. Кроме того, позволяет реагировать на события,
     * создаваемые классом Order или его родителями
     */
//    public function behaviors()
//    {
//        return [
//            'timestamp' => [
//                'class' => TimestampBehavior::class,
//                'attributes' => [
//                    // при вставке новой записи присвоить атрибутам created
//                    // и updated значение метки времени UNIX
//                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'], //,'updated'],
//                    // при обновлении существующей записи  присвоить атрибуту
//                    // updated значение метки времени UNIX
//                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
//                ],
//                // если вместо метки времени UNIX используется DATETIME
//                'value' => new Expression('NOW()'),
////                'value' => function() {
////                    return gmdate("Y-m-d H:i:s");
////                }
//            ],
//
//        ];
//    }

    /**
     * get messages write users
     *
     * @param $idTo
     * @param $idFrom
     * @return array|ActiveRecord[]
     */
    public function getMessagesWriteUsers($idTo, $idFrom)
    {
        return $this::find()
            ->select('id_from, name_from, message_from, name_to, date')
            ->where(['in', 'id_from', [$idFrom, $idTo]])
            ->andWhere(['in', 'id_to', [$idTo, $idFrom]])
            ->all();
    }


    public function getMessagesWriteUsersss($idTo, $idFrom)
    {
        return $this::find()
            ->select('id_from, name_from, message_from, name_to, date')
            ->where(['in', 'id_from', [$idFrom, $idTo]])
            ->andWhere(['in', 'id_to', [$idTo, $idFrom]])
            ->asArray()
            ->all();
    }

}
