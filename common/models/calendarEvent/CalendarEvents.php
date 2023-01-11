<?php

namespace common\models\calendarEvent;

use Yii;

/**
 * This is the model class for table "calendar_events".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $event
 * @property string|null $created_at
 */
class CalendarEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['created_at'], 'safe'],
            [['event'], 'string', 'max' => 64],
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
            'event' => 'Event',
            'created_at' => 'Created At',
        ];
    }
}
