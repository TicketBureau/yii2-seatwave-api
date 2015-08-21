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
     *
     * @throws InvalidConfigException
     *
     * @return array list of attribute names.
     */
    public function attributes()
    {
        throw new InvalidConfigException('The attributes() method of the ActiveRecord has to be implemented by child classes.');
    }

    /**
     * Returns the entity which is related the model to.
     * @throws InvalidConfigException
     *
     * @returns string
     */
    public static function tableName()
    {
        throw new InvalidConfigException('The tableName() method of the ActiveRecord has to be implemented by child classes.');
    }

    /**
     * @throws InvalidConfigException
     *
     * @return string
     */
    public function getSource()
    {
        throw new InvalidConfigException('The getSource() method of the ActiveRecord has to be implemented by child classes if you need to save data.');
    }

    /**
     * Returns the list of all post attributes names of the model.
     * This method must be overridden by child classes to define available attributes.
     *
     * @throws InvalidConfigException
     *
     * @return array list of attribute names.
     */
    public static function postAttributes()
    {
        throw new InvalidConfigException('The postAttributes() method of the ActiveRecord has to be implemented by child classes if you need to define post attributes.');
    }

    /**
     * @inheritdoc
     */
    public function insert($runValidation = true, $attributes = null)
    {
        $db = ActiveRecord::getDb();
        return $db->executeCommand('SAVE', $this->tableName(), $this->getSource(), $this->prepareQueryString(), 'https://');
    }

    /**
     * Returns params that are set by default in the Model.
     * @return array
     */
    public static function additionalParams()
    {
        return ['GET' => [], 'POST' => []];
    }

    public function prepareQueryString() {
        /* @var $modelClass ActiveRecord */
        $modelClass = get_class($this);

        $attributes = [];
        foreach($this->getAttributes() as $key => $value) {
            $method = 'GET';
            if(in_array($key, $modelClass::postAttributes())) {
                $method = 'POST';
            }
            $attributes[$method][$key] = $this->$key;
        }

        return array_merge_recursive($attributes, $modelClass::additionalParams());
    }
}
