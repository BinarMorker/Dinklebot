<?php
class ApiRequest {

	private $API_KEY = "X-API-Key: 8147443ac3d64b238d680f89b912e285";
	private $response;
	private $error_code;

	public function __construct($url) {
		$options = array(
			"http" => array(
				"method" => "GET",
				"header" => $this->API_KEY
			)
		);
		$context = stream_context_create($options);
		$response = json_decode(file_get_contents($url, false, $context), false);
		if ($response->ErrorCode == 1 && $response->Response != null) {
			$this->response = $response->Response;
			$this->error_code = 1;
		} else {
			$this->response = null;
			$this->error_code = -1;
		}
	}

	public function get_response() {
		return $this->response;
	}

	public function get_error_code() {
		return $this->error_code;
	}

}