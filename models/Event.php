<?php

namespace ticketbureau\seatwave\models;

use Yii;
use ticketbureau\seatwave\ActiveRecord;
use ticketbureau\seatwave\ActiveDataProvider;


class Event extends ActiveRecord
{
    public $limit = 50;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Events';
    }

    /**
     * @return array
     */
    public static function primaryKey()
    {
        return ['Id'];
    }

    /**
     * @return array
     */
    public static function additionalParams()
    {
        return ['GET' => ['siteId' => 4]];
    }

    /**
     * @return array
     */
    public function attributes() {
        return ['Date', 'Details', 'VenueName', 'Town', 'Country', 'Ticketcount', 'Currency', 'MinPrice', 'SwURL', 'EventGroupImageURL', 'LayoutId', 'EventGroupId', 'VenueID', 'what', 'where', 'whenFrom', 'whenTo', 'maxPrice', 'eventsWithoutTix'];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['what', 'where', 'whenFrom', 'whenTo', 'maxPrice', 'eventsWithoutTix'], 'string'],
            [['whenFrom', 'whenTo'], 'date'],
        ];
    }

    /**
     * @param $eventGroupId
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public function getByEventGroup($eventGroupId) {
        return $this->hasMany(Event::className(), ['eventgroup' => $eventGroupId]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $eventGroupId = null)
    {

        if(!empty($eventGroupId)) {
            $eventGroup = new Event;
            $query = $eventGroup->getByEventGroup($eventGroupId);
        } else {
            $query = Event::find();

            $this->load($params);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $className = explode('\\',Event::className());
        $className = $className[count($className) - 1];
        $query->where([
            'what' => isset($params[$className]['what']) ? $params[$className]['what'] : '',
            'where' => isset($params[$className]['where']) ? $params[$className]['where'] : '',
            'when_from' => isset($params[$className]['whenFrom']) ? $params[$className]['whenFrom'] : '',
            'when_to' => isset($params[$className]['whenTo']) ? $params[$className]['whenTo'] : '',
            'max_price' => isset($params[$className]['maxPrice']) ? $params[$className]['maxPrice'] : '',
            'eventsWithoutTix' => isset($params[$className]['eventsWithoutTix']) ? 'true' : 'false',
        ]);

        return $dataProvider;
    }
}
