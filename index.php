<?php

include("util/Cache.php");

if (!empty($_GET['l'])) {
	$language = $_GET['l'];
} else {
	$language = "en";
}

if (!empty($_GET['u']) && !empty($_GET['c'])) {
	$username = rawurlencode(trim($_GET['u']));
	$console = $_GET['c'];
	$cache = new Cache();
	//$cache->start();
	include("util/StartupRequests.php");
	include("view/Header.php");
	include("view/Player.php");
	//include("view/Exotics.php");
	//include("view/Achievements.php");
	//include("view/Footer.php");
	//$cache->close();
} else {
	die();
}

?>