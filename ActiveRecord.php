<?php


namespace ticketbureau\seatwave;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

class ActiveRecord extends BaseActiveRecord
{
    /**
     * Returns the database connection used.
     * By default, the "api" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('api');
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    /**
     * Returns the primary key name(s) for this AR class.
     * This method should be overridden by child classes to define the primary key.
     *
     * Note that an array should be returned even when it is a single primary key.
     *
     * @return string[] the primary keys of this record.
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Returns the list of all attribute names of the model.
     * This method must be overridden by child classes to define available attributes.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        throw new InvalidConfigException('The attributes() method of redis ActiveRecord has to be implemented by child classes.');
    }

    /**
     * @inheritdoc
     */
    public function insert($runValidation = true, $attributes = null)
    {
        throw new InvalidConfigException('Method not implemented.');
    }

    /**
     * Returns params that are set by default in the Model.
     * @return array
     */
    public static function additionalParams()
    {
        return [];
    }
}
