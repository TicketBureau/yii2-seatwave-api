<?php

namespace ticketbureau\seatwave;

use yii\base\Component;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRelationTrait;
use yii\db\QueryTrait;

class ActiveQuery extends Component implements ActiveQueryInterface
{
    use QueryTrait;
    use ActiveQueryTrait;
    use ActiveRelationTrait;

    /**
     * @event Event an event that is triggered when the query is initialized via [[init()]].
     */
    const EVENT_INIT = 'init';


    /**
     * Constructor.
     *
     * @param array $modelClass the model class associated with this query
     * @param array $config     configurations to be applied to the newly created query object
     */
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }

    /**
     * Initializes the object.
     * This method is called at the end of the constructor. The default implementation will trigger
     * an [[EVENT_INIT]] event. If you override this method, make sure you call the parent implementation at the end
     * to ensure triggering of the event.
     */
    public function init()
    {
        parent::init();
        $this->trigger(self::EVENT_INIT);
    }

    /**
     * Executes the query and returns all results as an array.
     *
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return array|ActiveRecord[] the query results. If the query results in nothing, an empty array will be returned.
     */
    public function all($db = null)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }

        return $db->executeCommand('ALL', $modelClass::tableName(), $this->prepareQueryString());
    }

    /**
     * Executes the query and returns a single row of result.
     *
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return ActiveRecord|array|null a single row of query result. Depending on the setting of [[asArray]],
     * the query result may be either an array or an ActiveRecord object. Null will be returned
     * if the query results in nothing.
     */
    public function one($db = null)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }

        return $db->executeCommand('ONE', $modelClass::tableName(), $this->prepareQueryString());
    }

    /**
     * Returns the number of records.
     *
     * @param string     $q  the COUNT expression. This parameter is ignored by this implementation.
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return integer number of records
     */
    public function count($q = '*', $db = null)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }
        $this->limit = 1;
        return $db->executeCommand('COUNT', $modelClass::tableName(), $this->prepareQueryString());
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     *
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return boolean whether the query result contains any row of data.
     */
    public function exists($db = null)
    {
        return $this->one($db) !== null;
    }

    public function prepareQueryString()
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;

        return array_merge(is_array($this->where) ? $this->where : [],
            ['pgsize' => $this->limit, 'pgnumber' => $this->offset + 1],
            $modelClass::additionalParams());
    }
}
