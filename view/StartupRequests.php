<?php

$url = 'http://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/'.$console.'/'.$username;
$request = new ApiRequest($url);
if ($request->get_error_code() != 1) {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
	exit;
}
$membership = $request->get_response()[0]; // Get membership details about the player

$url = 'http://www.bungie.net/Platform/Destiny/'.$membership->membershipType.'/Account/'.$membership->membershipId."/?definitions=true&lc=".$language;
$response = (new ApiRequest($url))->get_response(); // Get the player instance
$account = $response->data;
$definitions = json_decode(json_encode($response->definitions), true);

$url = "http://www.bungie.net/Platform/Destiny/Stats/Account/".$account->membershipType."/".$account->membershipId;
$globalStats = (new ApiRequest($url))->get_response();
$url = "http://www.bungie.net/Platform/Destiny/Stats/Definition/?lc=".$language;
$response = (new ApiRequest($url))->get_response();
$statDefs = json_decode(json_encode($response), true);

$url = "https://www.bungie.net/platform/destiny/advisors/?definitions=true&lc=".$language;
$response = (new ApiRequest($url))->get_response();
$advisors = $response->data;
$advisorsDefs = json_decode(json_encode($response->definitions), true);
//$advisors->events->events[0] = json_decode('{"eventHash":2,"eventIdentifier":"SPECIAL_EVENT_IRON_BANNER","expirationDate":"2015-04-21T09:00:00Z","startDate":"2015-04-14T09:00:00Z","expirationKnown":true}');