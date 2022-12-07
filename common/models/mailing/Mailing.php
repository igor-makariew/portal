<?php

namespace common\models\mailing;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "mailing".
 *
 * @property int $id
 * @property int $id_user_mailing
 * @property string|null $subject
 * @property string|null $textbody
 * @property string|null $created_at
 */
class Mailing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mailing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user_mailing'], 'required'],
            [['id_user_mailing'], 'integer'],
            [['textbody'], 'string'],
            [['created_at'], 'safe'],
            [['subject'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user_mailing' => 'Id User Mailing',
            'subject' => 'Subject',
            'textbody' => 'Textbody',
            'created_at' => 'Created At',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'], //,'updated'],
                    // при обновлении существующей записи  присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
                // если вместо метки времени UNIX используется DATETIME
                'value' => new Expression('NOW()'),
//                'value' => function() {
//                    return gmdate("Y-m-d H:i:s");
//                }
            ],

        ];
    }

    /**
     * send message
     *
     * @param $data
     * @return bool
     */
    public function sendEmail($data)
    {
        return    Yii::$app->mailer->compose()
                    ->setTo($data['email'])
                    ->setFrom('www.disigner@yandex.ru')
                    ->setSubject($data['subject'])
                    ->setTextBody($data['textbody'])
                    ->send();
    }

    /**
     * @param array $listUsers
     * @param array $data
     * @renurb void
     */
    public function saveManyData($listUsers, $data): void
    {
        foreach ($listUsers as $user) {
            $mailing = new $this();
            $mailing->id_user_mailing = Yii::$app->user->id;
            $mailing->subject = $data['subject'];
            $mailing->textbody = $data['textbody'];
            $mailing->username = $user['username'];
            $mailing->email = $user['email'];
            $mailing->phone = $user['phone'];
            $mailing->sent = 0;
            $mailing->save();
        }
    }

}
