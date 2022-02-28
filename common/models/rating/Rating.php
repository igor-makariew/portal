<?php

namespace common\models\rating;

use common\models\listResorts\ListResorts;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rating".
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $rating
 * @property int|null $resorts_id
 */
class Rating extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'resorts_id'], 'integer'],
            [['rating'], 'number'],
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
            'rating' => 'Rating',
            'resorts_id' => 'Resorts ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListResotrs()
    {
        return $this->hasOne(ListResorts::class, ['resorts_id' => 'resorts_id']);
    }
}
