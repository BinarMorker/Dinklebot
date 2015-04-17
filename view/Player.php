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
			<a class='btn btn-dark' href='http://www.facebook.com/sharer/sharer.php?u=//mastodon.tk/<?=$console?>/<?=$username?>/<?=$language?>&t=<?=$membership->displayName?>' 
				onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
				target='_blank'><i class='glyphicon glyphicon-share'></i>&nbsp;<?=Language::get($language, "button_share")?></a>
			<a class='btn btn-dark' href='http://mastodon.tk/<?=$console?>/<?=$username?>/<?=$language?>' target='_blank'>
				<i class='glyphicon glyphicon-link'></i>&nbsp;<?=Language::get($language, "button_permalink")?>
			</a>
			<a class='btn btn-dark' href='/mastodon/<?=$console?>/<?=$username?>/<?=$language?>/refresh'>
				<i class='glyphicon glyphicon-refresh'></i>&nbsp;<?=Language::get($language, "button_reload")?>
			</a>
		</div>
	</div>
	<hr/>
	<div id='characters' class='row text-center'>
<?php foreach ($account->characters as $key => $character) {
	$prestigeClass = $character->isPrestigeLevel ? " class='prestige'" : ""; 
	$gender = $character->characterBase->genderType ? "Female" : "Male"; ?>
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
						<h3>In development, sorry!</h3>
					</div>
				</div>
				<div role="tabpanel" id="weekly-<?=$character->characterBase->characterId?>" class="tab-pane weekly">
					<?php 
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=AllPvP&count=25&lc=".$language;
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
					$url = "https://www.bungie.net/Platform/Destiny/Stats/ActivityHistory/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?mode=Strike&count=10&lc=".$language;
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
					$url = "http://www.bungie.net/Platform/Destiny/".$account->membershipType."/Account/".$account->membershipId."/Character/".$character->characterBase->characterId."/Inventory/?definitions=true&lc=".$language;
					$response = (new ApiRequest($url))->get_response();
					$inventory = json_decode(json_encode($response->definitions), true);
					foreach ($response->data->buckets->Equippable as $index => $item) {
						if ($index == 0) {
							$card = new SubclassCard($item->items[0], $inventory, $character->characterBase->stats, $language);
							$card->display();
							continue;
						}
						if (array_key_exists(0, $item->items)) {
							$card = new ItemCard($item->items[0], $inventory, $language); 
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
							/*if (($hash == "2161005788" || $hash == "594203991") && (empty((array)$advisors->events->events) || (!empty((array)$advisors->events->events) && $advisors->events->events[0]->eventIdentifier != "SPECIAL_EVENT_IRON_BANNER"))) {
								continue;
							}*/
						 	$card = new ProgressionCard($prog, $progDefs['progressions'], $language);
						 	$card->display();
						}
					} ?>
				</div>
				<div role="tabpanel" id="medals-<?=$character->characterBase->characterId?>" class="tab-pane medals">
					<?php
					$url = "https://www.bungie.net/Platform/Destiny/Stats/".$account->membershipType."/".$account->membershipId."/".$character->characterBase->characterId."/?modes=allPvP&groups=Medals";
					$response = (new ApiRequest($url))->get_response();
					if (!empty((array)$response->allPvP)) {
						$medals = $response->allPvP->allTime;
						?>
						<div class="medalcard">
							<?php /* ?><h4><?=$statDefs[$medals->allMedalsScore->statId]['statName']?>: <?=$medals->allMedalsScore->basic->value?></h4>
							<h5><?=$statDefs[$medals->allMedalsEarned->statId]['statName']?>: <?=$medals->allMedalsEarned->basic->value?></h5>
							<hr/><?php */ ?>
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
							<h4><?=Language::get($language, "never_entered_pvp")?></h4>
						</div>
					<?php } ?>
				</div>
				<div role="tabpanel" id="statistics-<?=$character->characterBase->characterId?>" class="tab-pane statistics">
					<?php
					foreach($globalStats->characters as $cs) {
						if($cs->characterId == $character->characterBase->characterId) {
							$charStats = $cs;
							break;
						}
					}
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
								<img src="/mastodon/img/global.png"/>
							</a>
						</li>
						<li role="story">
							<a href="#story-<?=$character->characterBase->characterId?>" 
								aria-controls="story-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="/mastodon/img/story.png"/>
							</a>
						</li>
						<?php if (property_exists($response->patrol, "allTime")) { ?>
						<li role="patrol">
							<a href="#patrol-<?=$character->characterBase->characterId?>" 
								aria-controls="patrol-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="/mastodon/img/patrols.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->allStrikes, "allTime")) { ?>
						<li role="strikes">
							<a href="#strikes-<?=$character->characterBase->characterId?>" 
								aria-controls="strikes-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="/mastodon/img/strikes.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->allPvP, "allTime")) { ?>
						<li role="crucible">
							<a href="#crucible-<?=$character->characterBase->characterId?>" 
								aria-controls="crucible-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="/mastodon/img/crucible.png"/>
							</a>
						</li>
						<?php } if (property_exists($response->raid, "allTime")) { ?>
						<li role="raid">
							<a href="#raid-<?=$character->characterBase->characterId?>" 
								aria-controls="raid-<?=$character->characterBase->characterId?>" 
								role="tab" data-toggle="tab">
								<img src="/mastodon/img/raids.png"/>
							</a>
						</li>
						<?php } ?>
					</ul>
					<div role="tabpanel" id="global-<?=$character->characterBase->characterId?>" class="tab-pane statcard active">
						<h4><?=Language::get($language, "menu_all")?></h4><hr class="small"/>
						<?=$global->display()?>
					</div>
					<div role="tabpanel" id="story-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_story")?></h4><hr class="small"/>
						<?=$story->display()?>
					</div>
					<?php if (property_exists($response->patrol, "allTime")) { ?>
					<div role="tabpanel" id="patrol-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_patrol")?></h4><hr class="small"/>
						<?=$patrol->display()?>
					</div>
					<?php } if (property_exists($response->allStrikes, "allTime")) { ?>
					<div role="tabpanel" id="strikes-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_strikes")?></h4><hr class="small"/>
						<?=$strikes->display()?>
					</div>
					<?php } if (property_exists($response->allPvP, "allTime")) { ?>
					<div role="tabpanel" id="crucible-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_crucible")?></h4><hr class="small"/>
						<?=$crucible->display()?>
					</div>
					<?php } if (property_exists($response->raid, "allTime")) { ?>
					<div role="tabpanel" id="raid-<?=$character->characterBase->characterId?>" class="tab-pane statcard">
						<h4><?=Language::get($language, "menu_raid")?></h4><hr class="small"/>
						<?=$raid->display()?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
<?php } ?>
	</div>
</div>