<?php

$url = 'https://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/'.$console.'/'.$username;
$request = new ApiRequest($url);
if ($request->get_error_code() != 1) {
	//header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	if (isset($_SESSION['previous_page'])) {
  	$_SESSION['post_data'] = array("title" => "This player doesn't exist", "message" => $username." does not seem to exist, does not play Destiny, or may be playing on another console.");
  	$_POST = $_SESSION;
		header('Location: ' . $_POST['previous_page']);
	}
	exit;
}
$membership = $request->get_response()[0]; // Get membership details about the player

$url = 'https://www.bungie.net/Platform/Destiny/'.$membership->membershipType.'/Account/'.$membership->membershipId."/?definitions=true&lc=".$language;
$request = new ApiRequest($url);
if ($request->get_error_code() != 1) {
	//header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	if (isset($_SESSION['previous_page'])) {
  	$_SESSION['post_data'] = array("title" => "This player doesn't exist", "message" => $username." exists, but has only participated in an early version of the game.");
  	$_POST = $_SESSION;
		header('Location: ' . $_POST['previous_page']);
	}
	exit;
}
$response = $request->get_response(); // Get the player instance
$account = $response->data;
$definitions = json_decode(json_encode($response->definitions), true);

$url = "https://www.bungie.net/Platform/Destiny/Stats/Account/".$account->membershipType."/".$account->membershipId."?lc=".$language;
$globalStats = (new ApiRequest($url))->get_response();
$url = "https://www.bungie.net/Platform/Destiny/Stats/Definition/?lc=".$language;
$response = (new ApiRequest($url))->get_response();
$statDefs = json_decode(json_encode($response), true);

$url = "https://www.bungie.net/platform/destiny/advisors/?definitions=true&lc=".$language;
$response = (new ApiRequest($url))->get_response();
$advisors = $response->data;
$advisorsDefs = json_decode(json_encode($response->definitions), true);