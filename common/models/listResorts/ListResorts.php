<?php

namespace common\models\listResorts;

use common\models\rating\Rating;
use common\models\listCountry\ListCountry;
use Yii;
use yii\db\ActiveRecord;
use common\traits\ListModelsTrait;

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
class ListResorts extends ActiveRecord
{
    use ListModelsTrait;
    const LIST_RESORTS = 'list resorts';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_resorts';
    }

    /*
     * проcлушка событий
     */
    public function init()
    {
        //Method 2
        $this->on(ListResorts::LIST_RESORTS, [$this, 'listResorts'],
            [
                'type' => 'delete',
                'extra_info' => null
            ]);
    }

    public function handlerListResorts()
    {
        $this->trigger(ListResorts::LIST_RESORTS);
    }

    public function listResorts($event)
    {
        return var_dump($event);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resorts_id', 'name', 'resort_country_id'], 'required'],
            [['resort_country_id', 'resorts_id'], 'integer'],
            ['is_popular' , 'safe'],
            ['rating', 'number', 'min' => 0, 'max' => 5],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resorts_id' => 'Resorts ID',
            'name' => 'Name',
            'is_popular' => 'Is Popular',
            'resort_country_id' => 'Resort Country ID',
            'at_filtering' => 'At Filtering',
            'rating' => 'Rating'
        ];
    }


    /**
     * @return \yii\db\ActiveQuery|ListCountry
     */
    public function getListCountry()
    {
        return $this->hasOne(ListCountry::class, ['country_id' => 'resort_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Rating::class, ['resorts_id' => 'resorts_id']);
    }
}
