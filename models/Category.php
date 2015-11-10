<?php

namespace ticketbureau\seatwave\models;

use Yii;
use ticketbureau\seatwave\ActiveRecord;
use ticketbureau\seatwave\ActiveDataProvider;


class Category extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Categories';
    }

    /**
     * @return array
     */
    public static function primaryKey() {
        return ['Id'];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'Id',
            'Name',
            'GenreId',
        ];
    }

    /**
     * @return array
     */
    public static function additionalParams()
    {
        return [];
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
        $query = Category::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
