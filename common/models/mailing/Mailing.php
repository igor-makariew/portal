<?php

namespace common\models\mailing;

use Yii;

/**
 * This is the model class for table "mailing".
 *
 * @property int $id
 * @property int $id_user_mailing
 * @property string|null $subject
 * @property string|null $textbody
 * @property string|null $created_at
 * @property string|null $username
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $sent
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
            [['id_user_mailing', 'sent'], 'integer'],
            [['textbody'], 'string'],
            [['created_at'], 'safe'],
            [['subject', 'email'], 'string', 'max' => 64],
            [['username'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 16],
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
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',
            'sent' => 'Sent',
        ];
    }
}
