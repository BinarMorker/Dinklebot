<?php

$database = DatabaseRequest::init($config->db_host, $config->database, $config->db_user, $config->db_pass);
$query = "INSERT IGNORE INTO users (`console`, `membership_id`) VALUES (?, ?);";
$request = new DatabaseRequest($database, $query, array((int)$account->membershipType, (string)$account->membershipId));
$request->send();

$query = "SELECT id FROM users WHERE console = ? AND membership_id = ?;";
$request = new DatabaseRequest($database, $query, array((int)$account->membershipType, (string)$account->membershipId));
$request->receive();
$userId = $request->get_result();

if (array_key_exists(0, $userId)) {
	$query = "SELECT id, hash FROM items WHERE hidden = 0";
	$request = new DatabaseRequest($database, $query, null);
	$request->receive();
	$itemList = $request->get_result();
	$total = $request->get_count();

	if ($account->membershipId == 1) {
		foreach($itemList as $key => $item) {
			if (ItemTypes::get((string)$item['itemHash'], "playstation")) {
				unset($itemList[$key]);
				$total--;
			}
		}
	}

	foreach ($account->characters as $character) {
		foreach ($character->characterBase->peerView->equipment as $item) {
			foreach ($itemList as $possibleItem) {
				if ((string)$item->itemHash == (string)$possibleItem['hash']) {
					$query = "INSERT IGNORE INTO users_items (`user_id`, `item_id`) VALUES (?, ?);";
					$request = new DatabaseRequest($database, $query, array($userId[0]['id'], $possibleItem['id']));
					$request->send();
					break;
				}
			}
		}
	}

	$query = "SELECT item_id FROM users_items WHERE user_id = ?;";
	$request = new DatabaseRequest($database, $query, array($userId[0]['id']));
	$request->receive();
	$itemList = $request->get_result();
	$count = $request->get_count();
}

?>

<div class='container' id='<?=$account->membershipId?>'>
	<div class='row'>
		<div class='col-lg-12 text-center'>
<?php if (!empty($account->clanName)) { ?>
			<span class='player-label'><?=$membership->displayName." // ".$account->clanName." // ".
				Language::get($language, "button_collection").": ".$count."/".$total?></span>
<?php } else { ?>
			<span class='player-label'><?=$membership->displayName." // ".
				Language::get($language, "button_collection").": ".$count."/".$total?></span>
<?php } ?>
			<div><span class='information'><i><?=Language::get($language, "info_refresh")?></i><img src="<?=$config->site_root?>/img/light.png" /> = <?=Language::get($language, "info_obtained")?></span></div>
			<a class='btn btn-dark' href='http://www.facebook.com/sharer/sharer.php?u=<?=$config->site_root?>/<?=$console?>/<?=$username?>/collection/<?=$language?>&t=<?=$membership->displayName?>' 
				onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
				target='_blank'><i class='glyphicon glyphicon-share'></i>&nbsp;<?=Language::get($language, "button_share")?></a>
			<a class='btn btn-dark' href='<?=$config->site_root?>/<?=$console?>/<?=$username?>/collection/<?=$language?>' target='_blank'>
				<i class='glyphicon glyphicon-link'></i>&nbsp;<?=Language::get($language, "button_permalink")?>
			</a>
			<a class='btn btn-dark' href='<?=$config->site_root?>/<?=$console?>/<?=$username?>/collection/<?=$language?>/refresh'>
				<i class='glyphicon glyphicon-refresh'></i>&nbsp;<?=Language::get($language, "button_reload")?>
			</a>
			<a class='btn btn-dark' href='<?=$config->site_root?>/<?=$console?>/<?=$username?>/<?=$language?>'>
				<i class='destiny-icon groups'></i>&nbsp;<?=Language::get($language, "button_characters")?>
			</a>
			<a class='btn btn-dark' href='<?=$config->site_root?>/<?=$console?>/<?=$username?>/grimoire/<?=$language?>'>
				<i class='destiny-icon grimoire'></i>&nbsp;<?=Language::get($language, "button_grimoire")?>
			</a>
		</div>
	</div>
	<hr/>
	<div id='collection' class='row'>
		<?php
			if (array_key_exists(0, $userId)) {
				$query = "SELECT id, name, rarity FROM categories WHERE hidden = 0;";
				$request = new DatabaseRequest($database, $query, null);
				$request->receive();
				$result = $request->get_result();

				if ($result != null) {
					foreach ($result as $value) { 
						switch($value['rarity']) {
							case 6: $tier = "exotic"; break;
							case 5: $tier = "legendary"; break;
							case 4: $tier = "rare"; break;
							case 3: $tier = "uncommon"; break;
							case 2:
							default: $tier = "common"; break;
						} ?>
						<div class="collection-card col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="collection-inner">
								<div class="card-bg<?php /* tier-<?=$tier?>*/ ?>">
									<h1><?=Language::get($language, $value['name'])?></h1>
								</div>
					<?php
						$query = "SELECT id, hash FROM categories_items JOIN items ON items.id = item_id WHERE category_id = ? AND hidden = 0";
						$request = new DatabaseRequest($database, $query, array($value['id']));
						$request->receive();
						$list = $request->get_result();

						if ($list != null) {
							foreach ($list as $item) {
								$obtained = false;

								foreach ($itemList as $itemCheck) {
									if ($item['id'] == $itemCheck['item_id']) {
										$obtained = true;
										break;
									}
								}

								/*ob_start();*/

								/*$url = "https://www.bungie.net/Platform/Destiny/Manifest/InventoryItem/".$item['hash']."/?definitions=true&lc=".$language;
								$apiRequest = new ApiRequest($url);
								$itemDefs = $apiRequest->get_response();
								$url = "https://www.bungie.net/Platform/Destiny/Manifest/TalentGrid/".$itemDefs->data->inventoryItem->talentGridHash."/?lc=".$language;
								$request = new ApiRequest($url);
								if ($request->get_response() != null) {
									$itemDefs->data->talentGrid = $request->get_response()->data->talentGrid;
								}*/

								/*echo json_encode($itemDefs);
								$cachefile = "items/".$item['hash'].".".$language.".json";
								$fp = fopen($cachefile, 'w'); 
								fwrite($fp, ob_get_contents()); 
								fclose($fp); 
								ob_end_flush(); */
								
								$itemDefs = json_decode(file_get_contents("items/".(string)$item['hash'].".".$language.".json"), false);

								$card = new ItemCollectionCard($itemDefs, $obtained, $language); 
								$card->display();
							}
						} ?>
							</div>
						</div>
					<?php
					}
				}
			}
		?>
	</div>
</div>