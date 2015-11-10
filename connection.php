<?php

namespace ticketbureau\seatwave;

use Yii;
use yii\base\Component;
use linslin\yii2\curl;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class Connection extends Component {

    public $endpoint = '';

    /** @var string TODO implement AUTH authentication */
    public $username = '';

    /** @var string TODO implement AUTH authentication */
    public $password = '';

    public $count_path = 'Paging.TotalResultCount';

    protected $_curl = null;

    /** @var  string Api Key for calling Seatwave api */
    public $apiKey;


    public function executeCommand($commandType,$entity, $source, $params = [], $protocol = 'http://'){

        if(!class_exists('\\linslin\\yii2\\curl\\Curl')) {
            throw new Exception('linslin\yii2\curl\Curl is needed.');
        }

        $curl = new curl\Curl();
        $url = $protocol.$this->endpoint.strtolower($source);

        $queryString = ['apiKey=' . $this->apiKey];
        foreach($params['GET'] as $key => $param) {
            if(!empty($param)) {
                $queryString[] = "$key=".urlencode($param);
            }
        }
        $queryString = implode('&', $queryString);
        $url .= '?'.$queryString;

        Yii::trace($url, __METHOD__);

        if(!empty($params['POST'])) {
            $raw_response = $curl->setOption(
                CURLOPT_POSTFIELDS,
                json_encode(array_filter($params['POST'])))
                ->post($url);
        } else {
            $raw_response = $curl->get($url);
        }

        if($raw_response !== false) {
            $response = json_decode($raw_response, true);

            if($response['Status']['Code'] !== 0) {
                throw new Exception('Code: '.$response['Status']['Code'].'. '.$response['Status']['Message']);
            }
            switch($commandType) {
                case 'COUNT':
                    return ArrayHelper::getValue($response, $this->count_path);
                case 'ALL':
                    return $response[$entity];
                case 'ONE':
                    return $response[$entity];
                case 'SAVE':
                    return $response;
            }
        } else {
            throw new Exception('Request Error with the following url: '. $url);
        }
    }
}