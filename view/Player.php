<?php

$validProgressions = array(
	"2030054750",
	/* "1774654531",
	"2193513588",
	"1707948164",
	"2158037182", */
	"529303302",
	"174528503",
	"2161005788",
	"452808717",
	"3233510749",
	"1357277120",
	"2778795080",
	"1424722124",
	"3871980777",
	"594203991",
	"2033897742",
	"2033897755"
);

?>

<div class='container' id='<?=$account->membershipId?>'>
	<div class='row'>
		<div class='col-lg-12 text-center'>
<?php if (!empty($account->clanName)) { ?>
			<span class='player-label'><?=$membership->displayName." // ".$account->clanName." // ".
				$definitions['items'][(string)$account->inventory->currencies[0]->itemHash]['itemName'].": ".$account->inventory->currencies[0]->value?></span>
<?php } else { ?>
			<span class='player-label'><?=$membership->displayName." // ".
				$definitions['items'][(string)$account->inventory->currencies[0]->itemHash]['itemName'].": ".$account->inventory->currencies[0]->value?></span>
<?php } ?>
			<span class='information'><i><?=Language::get($language, "info_refresh")?></i></span>
			<a class='btn btn-dark' href='http://www.facebook.com/sharer/sharer.php?u=<?=$site_root?>/<?=$console?>/<?=$username?>/<?=$language?>&t=<?=$membership->displayName?>' 
				onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
				target='_blank'><i class='glyphicon glyphicon-share'></i>&nbsp;<?=Language::get($language, "button_share")?></a>
			<a class='btn btn-dark' href='<?=$site_root?>/<?=$console?>/<?=$username?>/<?=$language?>' target='_blank'>
				<i class='glyphicon glyphicon-link'></i>&nbsp;<?=Language::get($language, "button_permalink")?>
			</a>
			<a class='btn btn-dark' href='<?=$site_root?>/<?=$console?>/<?=$username?>/<?=$language?>/refresh'>
				<i class='glyphicon glyphicon-refresh'></i>&nbsp;<?=Language::get($language, "button_reload")?>
			</a>
		</div>
	</div>
	<hr/>
	<div id='characters' class='row text-center'>
<?php foreach ($account->characters as $key => $character) {
	$prestigeClass = $character->isPrestigeLevel ? " class='prestige'" : ""; 
	$gender = $character->characterBase->genderType ? "Female" : "Male";
	$url = "http://www.bungie.net/Platform/Destiny/".$account->membershipType."/Account/".$account->membershipId."/Character/".$character->characterBase->characterId."/Inventory/?definitions=true&lc=".$language;
	$response = (new ApiRequest($url))->get_response();
	$inventory = $response->data;
	$inventoryDefs = json_decode(json_encode($response->definitions), true); ?>
		<div id='character-<?=$character->characterBase->characterId?>' class='col-md-4'>
			<div class='character-label equip' style='background:url(<?=Cache::base64Convert("http://www.bungie.net".$character->backgroundPath)?>);background-size:cover;background-repeat:no-repeat'>
				<img src='<?=Cache::base64Convert("http://www.bungie.net".$character->emblemPath)?>'/>
				<h2><?=$definitions['classes'][(string)$character->characterBase->classHash]['className'.$gender]?><br/>
				<small><?=$definitions['races'][(string)$character->characterBase->raceHash]['raceName'.$gender]?></small></h2>
				<h3<?=$prestigeClass?>><?=$character->characterLevel?><br/>
				<small><img src='<?=Cache::base64Convert("http://www.bungie.net/img/theme/destiny/icons/icon_grimoire_lightgray.png")?>'><?=$account->grimoireScore?></small></h3>
				<div class='progress-bar'>
					<div<?=$prestigeClass?> style='width:<?=$character->percentToNextLevel?>%'></div>
				</div>
			</div>
			<div class="character-content">
				<ul class="nav nav-tabs main-nav">
					<li role="presentation" class="active">
						<a href="#overview-<?=$character->characterBase->characterId?>" 
							aria-controls="overview-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_overview")?>
						</a>
					</li>
					<li role="presentation">
						<a href="#weekly-<?=$character->characterBase->characterId?>" 
							aria-controls="weekly-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_weekly")?>
						</a>
					</li>
					<li role="presentation">
						<a href="#equipment-<?=$character->characterBase->characterId?>" 
							aria-controls="equipment-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_equipment")?>
						</a>
					</li>
					<li role="presentation">
						<a href="#progression-<?=$character->characterBase->characterId?>" 
							aria-controls="progression-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_progression")?>
						</a>
					</li>
					<li role="presentation">
						<a href="#statistics-<?=$character->characterBase->characterId?>" 
							aria-controls="statistics-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_statistics")?>
						</a>
					</li>
					<li role="presentation">
						<a href="#medals-<?=$character->characterBase->characterId?>" 
							aria-controls="medals-<?=$character->characterBase->characterId?>" 
							role="tab" data-toggle="tab">
							<?=Language::get($language, "menu_medals")?>
						</a>
					</li>
				</ul>
				<div role="tabpanel" id="overview-<?=$character->characterBase->characterId?>" class="tab-pane overview active">
					<div class="medalcard">
						<img class="player-model" src="<?=$site_root?>/img/character.png" height="350px"/>
						<hr/>
						<div class="player-overview">
							<?php
							foreach($globalStats->characters as $cs) {
								if($cs->characterId == $character->characterBase->characterId) {
									$charStats = $cs;
									break;
								}
							} ?>
							<h4>
							<?php $days = floor($character->characterBase->minutesPlayedTotal / (60 * 24));
							if ($days > 0) {
								$days = $days . Language::get($language, "time_day");
							} else {
								$days = "";
							}
							$hours = floor($character->characterBase->minutesPlayedTotal / 60) - ($days * 24);
							if ($hours > 0) {
								$hours = $hours . Language::get($language, "time_hour");
							} else {
								$hours = "";
							}
							$minutes = $character->characterBase->minutesPlayedTotal - ($hours * 60) - ($days * 24 * 60);
							if ($minutes > 0) {
								$minutes = $minutes . Language::get($language, "time_minute");
							} else {
								$minutes = "";
							}
							echo Language::get($language, "time_played") . $days . $hours . $minutes . Language::get($language, "time_played_f");
							?><br/>
							<small><?=Language::get($language, "time_active") . ": " . $charStats->merged->allTime->secondsPlayed->basic->displayValue?></small></h4>
							<hr class="small"/>
							<div class="row">
								<div class="col-xs-6">
									<?php $stat = $character->characterBase->stats->STAT_DEFENSE; ?>
									<strong><?=$definitions['stats'][(string)$stat->statHash]['statName']?></strong><br/>
									<span><?=$stat->value?></span>
								</div>
								<div class="col-xs-6">
									<?php @$stat = $character->characterBase->stats->STAT_LIGHT; if ($stat != null) { ?>
									<strong><?=$definitions['stats'][(string)$stat->statHash]['statName']?></strong><br/>
									<span><?=$stat->value?></span>
									<?php } else { ?>
									<span><?=Language::get($language, "info_light_twenty")?></span>
									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-4">
									<?php $stat = $character->characterBase->stats->STAT_INTELLECT; ?>
									<strong><?=$definitions['stats'][(string)$stat->statHash]['statName']?></strong><br/>
									<span><?=$stat->value?></span>
								</div>
								<div class="col-xs-4">
									<?php $stat = $character->characterBase->stats->STAT_DISCIPLINE; ?>
									<strong><?=$definitions['stats'][(string)$stat->statHash]['statName']?></strong><br/>
									<span><?=$stat->value?></span>
								</div>
								<div class="col-xs-4">
									<?php $stat = $character->characterBase->stats->STAT_STRENGTH; ?>
									<strong><?=$definitions['stats'][(string)$stat->statHash]['statName']?></strong><br/>
									<span><?=$stat->value?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<strong><?=$inventoryDefs['items'][(string)$inventory->currencies[1]->itemHash]['itemName']?></strong><br/>
									<span><?=$inventory->currencies[1]->value?></span>
								</div>
								<div class="col-xs-6">
									<strong><?=$inventoryDefs['items'][(string)$inventory->currencies[2]->itemHash]['itemName']?></strong><br/>
									<span><?=$inventory->currencies[2]->value?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" id="weekly-<?=$character->characterBase->characterId?>" class="tab-pane weekly">
					<?php 
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=AllPvP&count=50&lc=".$language;
					$activities = (new ApiRequest($url))->get_response()->data;
					$hash = (string)$advisors->dailyCrucibleHash;
					$card = new ActivityCard($hash, $advisorsDefs, $activities, $advisors->dailyCrucibleResetDate, "1 day", $advisorsDefs['activities'][(string)$hash], $language);
					$card->display(false);
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=Story&count=25&lc=".$language;
					$activities = (new ApiRequest($url))->get_response()->data;
					$completed = false;
					foreach(array_reverse($advisors->dailyChapterHashes) as $hash) {
						$card = new ActivityCard($hash, $advisorsDefs, $activities, $advisors->dailyChapterResetDate, "1 day", $advisorsDefs['activities'][(string)$hash], $language);
						if ($card->completed) $completed = $card->completed;
						if ($completed) $card->completed = $completed;
						$card->display();
					}
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=Strike&count=50&lc=".$language;
					$activities = (new ApiRequest($url))->get_response()->data;
					$completed = false;
					foreach(array_reverse($advisors->heroicStrikeHashes) as $hash) {
						$card = new ActivityCard($hash, $advisorsDefs, $activities, $advisors->heroicStrikeResetDate, "1 week", $advisorsDefs['activities'][(string)$hash], $language);
						if ($card->completed) $completed = $card->completed;
						if ($completed) $card->completed = $completed;
						$card->display();
					}
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=Nightfall&count=10&lc=".$language;
					$activities = (new ApiRequest($url))->get_response()->data;
					$hash = (string)$advisors->nightfallActivityHash;
					$card = new ActivityCard($hash, $advisorsDefs, $activities, $advisors->nightfallResetDate, "1 week", $advisorsDefs['activities'][(string)$hash], $language);
					$card->display();
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=Raid&count=10&lc=".$language;
					$response = (new ApiRequest($url))->get_response();
					$activities = $response->data;
					$hashes = array("2659248068", "2659248071", "1836893119", "1836893116");
					foreach($hashes as $hash) {
						$url = "https://www.bungie.net/Platform/Destiny/Manifest/Activity/".$hash."/?definitions=true&lc=".$language;
						$defs = json_decode(json_encode((new ApiRequest($url))->get_response()), true);
						$card = new ActivityCard($hash, $defs['definitions'], $activities, $advisors->nightfallResetDate, "1 week", $defs['data']['activity'], $language);
						$card->display();
					}
					?>
				</div>
				<div role="tabpanel" id="equipment-<?=$character->characterBase->characterId?>" class="tab-pane equipment">
					<?php 
					foreach ($inventory->buckets->Equippable as $index => $item) {
						if ($index == 0) {
							$card = new SubclassCard($item->items[0], $inventoryDefs, $character->characterBase->stats, $language);
							$card->display();
							continue;
						}
						if (array_key_exists(0, $item->items)) {
							$card = new ItemCard($item->items[0], $inventoryDefs, $language); 
							$card->display();
						}
					} ?>
				</div>
				<div role="tabpanel" id="progression-<?=$character->characterBase->characterId?>" class="tab-pane progression">
					<?php 
					$url = "http://www.bungie.net/Platform/Destiny/".$account->membershipType."/Account/".$account->membershipId."/Character/".$character->characterBase->characterId."/Progression/?definitions=true&lc=".$language;
					$response = (new ApiRequest($url))->get_response();
					$progDefs = json_decode(json_encode($response->definitions), true); 
					foreach ($response->data->progressions as $index => $prog) { 
						$hash = $prog->progressionHash;
						if (in_array($hash, $validProgressions)) {
						 	$card = new ProgressionCard($prog, $progDefs['progressions'], $language);
						 	$card->display();
						}
					} ?>
				</div>
				<div role="tabpanel" id="medals-<?=$character->characterBase->characterId?>" class="tab-pane medals">
					<?php
					$url = "https://www.bungie.net/Platform/Destiny/Stats/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?modes=allPvP&groups=Medals";
					$response = (new ApiRequest($url))->get_response();
					$allPvP = (array)$response->allPvP;
					if (!empty($allPvP)) {
						$medals = $response->allPvP->allTime;
						?>
						<div class="medalcard">
							<h4><?=$statDefs[$medals->allMedalsScore->statId]['statName']?>: <?=$medals->allMedalsScore->basic->value?></h4>
							<h5><?=$statDefs[$medals->allMedalsEarned->statId]['statName']?>: <?=$medals->allMedalsEarned->basic->value?></h5>
							<hr/>
							<?php
							foreach($medals as $id => $medal) {
								if ($id != "activitiesEntered" && $id != "allMedalsScore" && $id != "allMedalsEarned" && $id != "medalsUnknown") {
									$card = new MedalCard($medal, $statDefs[(string)$id], $language);
									$card->display();
								}
							}
							?>
						</div>
					<?php } else { ?>
						<div class="medalcard">
							<h4><?=Language::get($language, "info_no_pvp")?></h4>
						</div>
					<?php } ?>
				</div>
				<div role="tabpanel" id="statistics-<?=$character->characterBase->characterId?>" class="tab-pane statistics">
					<?php
					$global = new StatList($charStats->merged->allTime, $statDefs, $language);
					$url = "http://www.bungie.net/Platform/Destiny/Stats/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?lc=".$language;
					$response = (new ApiRequest($url))->get_response();
					$story = new StatList($response->story->allTime, $statDefs, $language);
					$count = 2;
					if (property_exists($response->raid, "allTime")) {
						$raid = new StatList($response->raid->allTime, $statDefs, $language);
						$count++;
					}
					if (property_exists($response->patrol, "allTime")) {
						$patrol = new StatList($response->patrol->allTime, $statDefs, $language);
						$count++;
					}
					if (property_exists($response->allPvP, "allTime")) {
						$crucible = new StatList($response->allPvP->allTime, $statDefs, $language);
						$count++;
					}
					if (property_exists($response->allStrikes, "allTime")) {
						$strikes = new StatList($response->allStrikes->allTime, $statDefs, $language);
						$count++;
					}
					switch ($count) {
						case 1: $countWord = "one"; break;
						case 2: $countWord = "two"; break;
						case 3: $countWord = "three"; break;
						case 4: $countWord = "four"; break;
						case 5: $countWord = "five"; break;
						case 6: $countWord = "six"; break;
					}
					?>
					<ul class="nav nav-tabs img <?=$countWord?>">
						<li role="global" class="active">
							<a href="#global-<?=$character->characterBase->characterId?>" 
								aria-controls="global-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/global.png"/>
							</a>
						</li>
						<li role="story">
							<a href="#story-<?=$character->characterBase->characterId?>" 
								aria-controls="story-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/story.png"/>
							</a>
						</li>
						<?php if (property_exists($response->patrol, "allTime")) { ?>
						<li role="patrol">
							<a href="#patrol-<?=$character->characterBase->characterId?>" 
								aria-controls="patrol-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/patrols.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->allStrikes, "allTime")) { ?>
						<li role="strikes">
							<a href="#strikes-<?=$character->characterBase->characterId?>" 
								aria-controls="strikes-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/strikes.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->allPvP, "allTime")) { ?>
						<li role="crucible">
							<a href="#crucible-<?=$character->characterBase->characterId?>" 
								aria-controls="crucible-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/crucible.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->raid, "allTime")) { ?>
						<li role="raid">
							<a href="#raid-<?=$character->characterBase->characterId?>" 
								aria-controls="raid-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="<?=$site_root?>/img/raids.png"/>
							</a>
						</li>
						<?php } ?>
					</ul>
					<div role="tabpanel" id="global-<?=$character->characterBase->characterId?>" class="tab-pane statcard active">
						<h4><?=Language::get($language, "menu_all")?></h4><hr/>
						<?=$global->display()?>
					</div>
					<div role="tabpanel" id="story-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_story")?></h4><hr/>
						<?=$story->display()?>
					</div>
					<?php if (property_exists($response->patrol, "allTime")) { ?>
					<div role="tabpanel" id="patrol-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_patrol")?></h4><hr/>
						<?=$patrol->display()?>
					</div>
					<?php } if (property_exists($response->allStrikes, "allTime")) { ?>
					<div role="tabpanel" id="strikes-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_strikes")?></h4><hr/>
						<?=$strikes->display()?>
					</div>
					<?php } if (property_exists($response->allPvP, "allTime")) { ?>
					<div role="tabpanel" id="crucible-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_crucible")?></h4><hr/>
						<?=$crucible->display()?>
					</div>
					<?php } if (property_exists($response->raid, "allTime")) { ?>
					<div role="tabpanel" id="raid-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_raid")?></h4><hr/>
						<?=$raid->display()?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
<?php } ?>
	</div>
</div>