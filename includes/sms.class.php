<?php

class AlphaSMS
{

    public $numbers;
    public $body;
    public $sender_id = '';
    private $api_key;
    private $api_url = 'https://api.dev.alpha.net.bd';

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return mixed
     */
    public function Send()
    {
        $postFields = [
            'api_key'   => $this->api_key,
            'to'        => $this->numbers,
            'msg'       => $this->body,
            'sender_id' => $this->sender_id
        ];

        $response = $this->curl_get_content($this->api_url . '/sendsms', 'POST', $postFields);

        return json_decode($response, false);

    }

    /**
     * @param $url
     * @param string $method
     * @param array $postfields
     * @return bool|string
     */
    private function curl_get_content($url, $method = 'GET', $postfields = [])
    {

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'POST') {

            curl_setopt($curl, CURLOPT_POST, true);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
        }

        $response = curl_exec($curl);
        $curl_error = curl_errno($curl);

        curl_close($curl);

        if ($curl_error) {
            return false;
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        $response = $this->curl_get_content($this->api_url . '/user/balance/?api_key=' . $this->api_key);

        return json_decode($response, false);
    }
}