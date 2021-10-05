<?php

class AlphaSMS {

	private $api_key;
	private $api_url = 'https://api.dev.alpha.net.bd/sendsms';

	public $numbers;
	public $body;
	public $sender_id = '';

	public function __construct($api_key)
	{
		$this->api_key = $api_key;
	}

	public function Send()
	{
		$postFields = array(
			'api_key' => $this->api_key,
			'to' => $this->numbers,
			'msg' => $this->body,
			'sender_id' => $this->sender_id
		);

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL            => $this->api_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => $postFields,
		]);

		$response = curl_exec($curl);
		$curl_error = curl_errno($curl);

		curl_close($curl);

		if ($curl_error) {
			return false;
		}

		return json_decode($response, false);

	}
}