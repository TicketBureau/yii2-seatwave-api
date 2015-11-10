<?php

namespace ticketbureau\seatwave\models;

use Yii;
use ticketbureau\seatwave\ActiveRecord;
use ticketbureau\seatwave\ActiveDataProvider;


class Purchase extends ActiveRecord
{
    public $limit = 50;
    protected static $secret = '9c37ea2ff6a3f5a85d02462abd8f1234';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Purchase';
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
        return [];
    }

    /**
     * @return array
     */
    public static function postAttributes(){
        return ['ApplicationId', 'CurrencyCode', 'TicketGroupId', 'ticketQty'];
    }

    /**
     * @return array
     */
    public function attributes() {
        return array_merge(['token', 'timestamp', 'purchaseId'], Purchase::postAttributes());
    }

    /**
     * @param $time
     */
    public function generateToken($time){

        $this->token = hash_hmac('sha256', $time, Purchase::$secret);
    }

    /**
     * @return string
     */
    public function getSource() {
        if(!empty($this->purchaseId)) {
            return "purchase/{$this->purchaseId}/confirmonaccount";
        } else {
            return 'purchase/holdtickets';
        }
    }
}
