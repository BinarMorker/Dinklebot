<?php
	$config = json_decode(file_get_contents("config.json"));
	include_once "util/DatabaseRequest.php";
	include_once "util/Language.php";
	include_once "util/ApiRequest.php";
	$database = DatabaseRequest::init($config->db_host, $config->database, $config->db_user, $config->db_pass);

	$query = "SELECT id, hash FROM items WHERE hidden = 0";
	$request = new DatabaseRequest($database, $query, null);
	$request->receive();
	$list = $request->get_result();

	if ($list != null) {
		foreach ($list as $item) {
			foreach (Language::get_languages() as $language) {
				ob_start();

				$url = "https://www.bungie.net/Platform/Destiny/Manifest/InventoryItem/".$item['hash']."/?definitions=true&lc=".$language;
				$apiRequest = new ApiRequest($url);
				$itemDefs = $apiRequest->get_response();
				$url = "https://www.bungie.net/Platform/Destiny/Manifest/TalentGrid/".$itemDefs->data->inventoryItem->talentGridHash."/?lc=".$language;
				$request = new ApiRequest($url);
				if ($request->get_response() != null) {
					$itemDefs->data->talentGrid = $request->get_response()->data->talentGrid;
				}

				echo json_encode($itemDefs);
				$cachefile = "items/".$item['hash'].".".$language.".json";
				$fp = fopen($cachefile, 'w'); 
				fwrite($fp, ob_get_contents()); 
				fclose($fp); 
				ob_end_flush();
			}
		}
	}