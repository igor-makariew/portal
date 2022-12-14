<?php

namespace common\models\comments;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int|null $comment_resort_id
 * @property string|null $comment
 * @property string|null $created_at
 */
class Comments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment_resort_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_resort_id' => 'Comment Resort ID',
            'comment' => 'Comment',
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

    public function search($param)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($param);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere('like', 'comment', $this->comment);
        return $dataProvider;
    }

    public function formName()
    {
        return 'str';
    }
}
