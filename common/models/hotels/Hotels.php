<?php

namespace common\models\hotels;

use Yii;

/**
 * This is the model class for table "hotels".
 *
 * @property int $id
 * @property string|null $location_name
 * @property string|null $label
 * @property string|null $location
 * @property string|null $full_name
 * @property string|null $hotel_id
 * @property string|null $location_id
 * @property string|null $hotel_name
 * @property string|null $price_form
 * @property string|null $price_percentile
 * @property string|null $stars
 * @property string|null $price_avg
 */
class Hotels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hotels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_name', 'label', 'location', 'full_name', 'hotel_name', 'price_percentile',], 'string', 'max' => 255],
            [['location_id', 'price_form', 'stars', 'price_avg', 'hotel_id'], 'number'],
            [['hotel_id'], 'unique'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'location_name' => 'Местоположение отеля',
            'label' => 'Название отеля',
            'location' => 'Локация',
            'full_name' => 'Название города и страны',
            'hotel_id' => 'id локации в базе',
            'location_id' => ' id локации в базе',
            'hotel_name' => 'Название отеля',
            'price_form' => 'Минимальная цена за проживание в номере отеля за указанный период',
            'price_percentile' => 'Распределение цен по долям',
            'stars' => 'Звезды',
            'price_avg' => 'Средняя цена за проживание в номере за указанный период',
        ];
    }

    /**
     *
     */

    /**
     * @param string $key
     * @param array $array
     * @return bool
     */
    public static function isValueInArray(string $key, array $array): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }

        return false;
    }

    /**
     * @param $array
     * @return false|string
     */
    public function arrayToJSON($array)
    {
        return json_encode($array);
    }
}
