<?php
include("util/ApiRequest.php");

$url = 'http://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/'.$console.'/'.$username;
$membership = (new ApiRequest($url))->get_response()[0]; // Get membership details about the player

$url = 'http://www.bungie.net/Platform/Destiny/'.$membership->membershipType.'/Account/'.$membership->membershipId."/?definitions=true&lc=".$language;
$response = (new ApiRequest($url))->get_response(); // Get the player instance
$account = $response->data;
//var_dump($account->characters[0]);
$definitions = json_decode(json_encode($response->definitions), true);
//var_dump($definitions);