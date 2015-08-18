<?php

namespace ticketbureau\seatwave;

use Yii;
use yii\base\Component;
use linslin\yii2\curl;
use yii\db\Exception;

class Connection extends Component {

    public $endpoint = '';

    /** @var string TODO implement AUTH authentication */
    public $username = '';

    /** @var string TODO implement AUTH authentication */
    public $password = '';

    public $method = 'GET';

    protected $_curl = null;

    public function executeCommand($name, $source, $params = []){

        if(!class_exists('\\linslin\\yii2\\curl\\Curl')) {
            throw new Exception('linslin\yii2\curl\Curl is needed.');
        }

        $curl = new curl\Curl();
        $url = $this->endpoint.strtolower($source);

        Yii::trace('Url set to:' . $url, __METHOD__);

        if($this->method == 'GET') {
            $queryString = [];
            foreach($params as $key => $param) {
                if(!empty($param)) {
                    $queryString[] = "$key=$param";
                }
            }
            $queryString = implode('&', $queryString);
            $url .= '?'.$queryString;
            $raw_response = $curl->get($url);
        } else {
            $raw_response = $curl->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($params))
                ->post($url);
        }

        if($raw_response !== false) {
            $response = json_decode($raw_response, true);
            switch($name) {
                case 'COUNT':
                    return $response['Paging']['TotalResultCount'];
                case 'ALL':
                    $source = explode('/', $source);
                    return $response[$source[count($source) - 1]];
                case 'ONE':
                    return $response[$source][0];
            }
        } else {
            throw new Exception('Request Error with the following url: '. $url);
        }

    }
}