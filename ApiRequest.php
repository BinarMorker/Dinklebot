<?php
class ApiRequest {

	protected static $API_KEY = "X-API-Key: 8147443ac3d64b238d680f89b912e285";
	protected $response;
	protected $error_code;

	public function __construct($url) {
		$options = array(
			"http" => array(
				"method" => "GET",
				"header" => self::$API_KEY
			)
		);
		$context = stream_context_create($options);
		$response = json_decode(file_get_contents($url, false, $context), false);
		@$this->response = $response->Response;
		$this->error_code = $response->ErrorCode;
	}

	public function get_response() {
		return $this->response;
	}

	public function get_error_code() {
		return $this->error_code;
	}

}