<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class AlphaSMS
{

    public $numbers;
    public $body;
    public $sender_id = '';
    private $api_key;
    private $api_url = 'https://api.sms.net.bd';

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

        $response = $this->sendRequest($this->api_url . '/sendsms', 'POST', $postFields);





        return json_decode($response);

    }

    /**
     * @param $url
     * @param string $method
     * @param array $postfields
     * @return bool|string
     */
    private function sendRequest($url, $method = 'GET', $postfields = [])
    {
        $args = [
            'timeout' => 45,
        ];

        if ($method === 'POST') {
            $args['body'] = $postfields;
            $request      = wp_remote_post($url, $args);
        } else {
            if (!empty($postfields)) {
                $url = add_query_arg($postfields, $url);
            }
            $request = wp_remote_get($url, $args);
        }

        if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
            return false;
        }

        return wp_remote_retrieve_body($request);
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        $response = $this->sendRequest($this->api_url . '/user/balance/?api_key=' . $this->api_key);

        return json_decode($response);
    }
}