<?php
$config = json_decode(file_get_contents("config.json"));

if(isset($config->debug) && $config->debug) {
	error_reporting(-1);
	ini_set('display_errors', 'On');
}

session_start();

foreach (glob("util/*.php") as $filename) {
    include_once $filename;
}

Language::load("en");

if (!empty($_GET['l'])) {
	if (Language::exists($_GET['l'])) {
		$language = $_GET['l'];
		Language::load($language);
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
		if ($item != null && $item != "" && !empty($item) && $item != "refresh" && $item != $language) {
			$url .= "/".$item;
		}
	}
	$url .= "/".$language;
	header("Location: ".$url);
	exit;
}

if (isset($_GET['t'])) {
	include_once("view/Thankyou.php");
	include_once("view/Footer.php");
	exit();
}

//var_dump(Language::get_missing($language));

$cache = new Cache();
//$cache->disable();

if (!empty($_GET['u']) && !empty($_GET['c']) && !empty($_GET['r']) && $_GET['r'] == "refresh") {
	$cache->remove();
	$url = "";
	$break = explode('/', $_SERVER['REQUEST_URI']);
	foreach($break as $item) {
		if ($item != null && $item != "" && !empty($item) && $item != "refresh" && $item != $language) {
			$url .= "/".$item;
		}
	}
	$url .= "/".$language;
	header("Location: ".$url);
	exit;
}

$url = "https://www.bungie.net/Platform/Destiny/Stats/Definition/?lc=".$language;
$request = new ApiRequest($url);

if ($request->get_error_code() == 5) {
	$_SESSION['post_data']['title'] = "Bungie is down";
	$_SESSION['post_data']['message'] = "This system is temporarily disabled for maintenance.";
} else {
	$response = $request->get_response();
	$statDefs = json_decode(json_encode($response), true);
}

if (isset($_SESSION['post_data'])) {
  $_POST = $_SESSION['post_data'];
  $_SERVER['REQUEST_METHOD'] = 'POST';
  unset($_SESSION['post_data']);
  $alert = "<div class='alert alert-danger alert-dismissible' role='alert'>
	  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	  <h4>".$_POST['title']."</h4><p>".$_POST['message']."</p>
	</div>";
}

if (empty($_GET['u']) && empty($_GET['c']) && empty($_GET['r'])) {
	include_once("view/Header.php");
	$cache->start();
	include_once("view/Footer.php");
	$cache->close();
	exit;
}

foreach (glob("model/*.php") as $filename) {
    include_once $filename;
}

if (!empty($_GET['u']) && !empty($_GET['c'])) {
	$username = rawurlencode(trim($_GET['u']));
	$console = $_GET['c'];
	include_once("view/StartupRequests.php");
	include_once("view/Header.php");
	$cache->start();
	if (!empty($_GET['o']) && $_GET['o'] == "grimoire") {
		include_once("view/Grimoire.php");
	} elseif (!empty($_GET['o']) && $_GET['o'] == "collection") {
		include_once("view/Collection.php");
	} else {
		include_once("view/Player.php");
	}
	include_once("view/Footer.php");
	$cache->close();
} else {
	//header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
	if (isset($_SESSION['previous_page'])) {
  	$_SESSION['post_data'] = array("title" => "Missing information", "message" => "You need to specify both the console and the username.");
  	$_POST = $_SESSION;
		header('Location: ' . $_POST['previous_page']);
	}
	exit;
}

$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

?>