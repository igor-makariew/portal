<?php

namespace common\models\listResorts;

use common\models\listCountry\ListCountry;
use Yii;

/**
 * This is the model class for table "list_resorts".
 *
 * @property int $id
 * @property int $resorts_id
 * @property string $name
 * @property int|null $is_popular
 * @property int $resort_country_id
 * @property int|null $at_filtering
 */
class ListResorts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_resorts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resorts_id', 'name', 'resort_country_id'], 'required'],
            [['resorts_id', 'resort_country_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['resorts_id'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resorts_id' => 'ID курорта',
            'name' => 'Название',
            'is_popular' => 'Популярность',
            'resort_country_id' => 'Идентификатор страны',
            'at_filtering' => 'Доступные курорты в базе',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|ListCountry
     */
    public function getListCountry()
    {
        return $this->hasOne(ListCountry::class, ['country_id' => 'resort_country_id']);
    }
}
