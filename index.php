<?php
include("util/Log.php");
include("util/Credis.php");
include("util/Resque.php");

foreach (glob("util/*.php") as $filename) {
    include $filename;
}

if (!empty($_GET['l'])) {
	if (Language::exists($_GET['l'])) {
		$language = $_GET['l'];
	} else {
		$language = Language::best();
		$url = "";
		$break = explode('/', $_SERVER['REQUEST_URI']);
		foreach($break as $item) {
			if ($item != null && $item != "" && !empty($item) && $item != "refresh" && $item != $_GET['l']) {
				$url .= "/".$item;
			}
		}
		$url .= "/".$language;
		header("Location: ".$url);
		exit;
	}
} else {
	$language = Language::best();
	$url = "";
	$break = explode('/', $_SERVER['REQUEST_URI']);
	foreach($break as $item) {
		if ($item != null && $item != "" && !empty($item) && $item != "refresh") {
			$url .= "/".$item;
		}
	}
	$url .= "/".$language;
	header("Location: ".$url);
	exit;
}

$cache = new Cache();

if (empty($_GET['u']) && empty($_GET['c']) && empty($_GET['r'])) {
	$cache->start();
	include("view/Header.php");
	include("view/Footer.php");
	$cache->close();
	exit;
}

foreach (glob("model/*.php") as $filename) {
    include $filename;
}

if (!empty($_GET['u']) && !empty($_GET['c']) && !empty($_GET['r']) && $_GET['r'] == "refresh") {
	$cache->remove();
	Resque::enqueue("defaut", "CacheJob", array(
		'username' => $_GET['u'],
		'console' => $_GET['c']
	), true);
	$url = "";
	$break = explode('/', $_SERVER['REQUEST_URI']);
	foreach($break as $item) {
		if ($item != null && $item != "" && !empty($item) && $item != "refresh") {
			$url .= "/".$item;
		}
	}
	header("Location: ".$url);
	exit;
}

echo "<!-- Selected language is ".$language." -->\n";

if (!empty($_GET['u']) && !empty($_GET['c'])) {
	$username = rawurlencode(trim($_GET['u']));
	$console = $_GET['c'];
	$cache->start();
	include("view/StartupRequests.php");
	include("view/Header.php");
	include("view/Player.php");
	include("view/Footer.php");
	$cache->close();
} else {
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request"); 
	exit;
}

?>