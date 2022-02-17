<?php

namespace common\models\listCountry;

use common\models\listResorts\ListResorts;
use Yii;

/**
 * This is the model class for table "list_hotels".
 *
 * @property int $id
 * @property int|null $country_id
 * @property string|null $name
 * @property int|null $popular
 */
class ListCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'popular'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['country_id'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'ID страны',
            'name' => 'Название',
            'popular' => 'Популярность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|ListCountry
     */
    public function getListResorts()
    {
        return $this->hasMany(ListResorts::class, ['resort_country_id' => 'country_id']);
    }
}
