<?php

require_once("ApiRequest.php");

class ApiProxy extends ApiRequest {

	public function __construct($url) {
		$options = array(
			"http" => array(
				"method" => "GET",
				"header" => self::$API_KEY
			)
		);
		$context = stream_context_create($options);
		try {
			@$response = file_get_contents("http://".$url, false, $context);
			if ($response === false) {
				header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
				exit;
			}
			@$this->response = $response;
			$this->error_code = 0;
		} catch (Exception $e) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
			exit;
		}
	}

}

if (isset($_GET['s'])) {
	if (isset($_GET['x'])) {
		header('Content-Type: application/json');
		$items = explode(',', $_GET['x']);
		$responses = array();
		foreach ($items as $item) {
			$proxy = new ApiProxy($_GET['s']."/".$item);
			array_push($responses, json_decode($proxy->get_response()));
		}
		echo json_encode($responses);
	} else {
		$proxy = new ApiProxy($_GET['s']);
		header('Content-Type: application/json');
		echo $proxy->get_response();
	}
}