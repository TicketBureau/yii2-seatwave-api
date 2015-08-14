# yii2-seatwave-api

This project is meant to be a easy way to connect with Seatwave API. All information about their API can be found at http://developer.seatwave.com/Api/Api/discovery.

Create a configuration in main.php with the following values

```php

    'components' => [
        ...
        'api' => [
            'class' => 'ticketbureau\seatwave\Connection',
            'endpoint' => 'http://api-sandbox.seatwave.com/v2/discovery/',
        ],
        ...
    ],
```
Here you have a sample model for Categories from Seatwave
```php
        <?php
        
        namespace seatwave\models;
        
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
        
            public static function primaryKey() {
                return ['Id'];
            }
        
            public function attributes()
            {
                return [
                    'Id',
                    'Name',
                    'GenreId',
                ];
            }
        
            public static function additionalParams()
            {
                return ['apiKey' => '4739E4694D0E482A92C9D0B478D83934']; //Public key api found http://developer.seatwave.com/API/method/GetCategories?apiName=discovery
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
```
