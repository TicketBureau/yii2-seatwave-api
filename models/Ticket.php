<?php

namespace ticketbureau\seatwave\models;

use Yii;
use ticketbureau\seatwave\ActiveRecord;
use ticketbureau\seatwave\ActiveDataProvider;


class Ticket extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TicketGroups';
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
        return ['TicketTypeName','TicketCount', 'FaceValueCurrency', 'FaceValue', 'Currency', 'EventID', 'EventID', 'maxPrice'];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['maxPrice'], 'string']
        ];
    }

    /**
     * @param $id
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public function getTicketsByEvent($ticketId)
    {
        return $this->hasMany(Ticket::className(), ['event' => $ticketId]);
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
            'maxPrice' => isset($params[$className]['maxPrice']) ? $params[$className]['maxPrice'] : '',
        ]);

        return $dataProvider;
    }
}
