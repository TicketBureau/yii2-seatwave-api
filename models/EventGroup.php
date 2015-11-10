<?php

namespace ticketbureau\seatwave\models;

use Yii;
use ticketbureau\seatwave\ActiveRecord;
use ticketbureau\seatwave\ActiveDataProvider;


class EventGroup extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'EventGroups';
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
        return ['Name','TicketCount', 'Currency', 'MinPrice', 'SwURL', 'ImageURL', 'CategoryId', 'what', 'where', 'whenFrom', 'whenTo', 'maxPrice', 'eventsWithoutTix'];
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
     * @return \yii\db\ActiveQuery
     */
    public function getEvents($id)
    {
        return $this->hasMany(Event::className(), ['eventgroup' => $id]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EventGroup::find();

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'attributes' => ['Name', 'MinPrice', 'CategoryId', 'TicketCount'],
            ],
        ]);

        $className = explode('\\',EventGroup::className());
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
