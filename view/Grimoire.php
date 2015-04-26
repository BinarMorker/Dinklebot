<?php

$url = "https://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/".$account->membershipType."/".$account->membershipId."/?definitions=true&lc=".$language;
$response = (new ApiRequest($url))->get_response();
$score = $response->data->score;
$cards = $response->data->cardCollection;
$bonuses = $response->data->bonuses;
$definitions = json_decode(json_encode($response->cardDefinitions), true);

?>

<div class='container' id='<?=$account->membershipId?>'>
	<div class='row'>
		<div class='col-lg-12 text-center'>
<?php if (!empty($account->clanName)) { ?>
			<span class='player-label'><?=$membership->displayName." // ".$account->clanName." // ".
				Language::get($language, "button_grimoire").": ".$score?></span>
<?php } else { ?>
			<span class='player-label'><?=$membership->displayName." // ".
				Language::get($language, "button_grimoire").": ".$score?></span>
<?php } ?>
			<span class='information'><i><?=Language::get($language, "info_refresh")?></i></span>
			<a class='btn btn-dark' href='http://www.facebook.com/sharer/sharer.php?u=<?=$site_root?>/<?=$console?>/<?=$username?>/grimoire/<?=$language?>&t=<?=$membership->displayName?>' 
				onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
				target='_blank'><i class='glyphicon glyphicon-share'></i>&nbsp;<?=Language::get($language, "button_share")?></a>
			<a class='btn btn-dark' href='<?=$site_root?>/<?=$console?>/<?=$username?>/grimoire/<?=$language?>' target='_blank'>
				<i class='glyphicon glyphicon-link'></i>&nbsp;<?=Language::get($language, "button_permalink")?>
			</a>
			<a class='btn btn-dark' href='<?=$site_root?>/<?=$console?>/<?=$username?>/grimoire/<?=$language?>/refresh'>
				<i class='glyphicon glyphicon-refresh'></i>&nbsp;<?=Language::get($language, "button_reload")?>
			</a>
			<a class='btn btn-dark' href='<?=$site_root?>/<?=$console?>/<?=$username?>/<?=$language?>'>
				<i class='destiny-icon groups'></i>&nbsp;<?=Language::get($language, "button_characters")?>
			</a>
		</div>
	</div>
	<hr/>
	<div id='grimoire' class='row grimoire-cards'>
		<?php
		$statCards = array();
		$loreCards = array();
		foreach ($cards as $info) {
			if (isset($info->statisticCollection) && !empty($info->statisticCollection)) {
				array_push($statCards, $info);
			} else {
				array_push($loreCards, $info);
			}
		}
		foreach ($statCards as $info) {
			$card = new GrimoireCard($info, $definitions[(string)$info->cardId], $language);
			$card->display();
		}
		?>
	</div>
	<hr/>
	<div id='grimoire-lore' class='row grimoire-cards'>
		<?php
		foreach ($loreCards as $info) {
			$card = new GrimoireCard($info, $definitions[(string)$info->cardId], $language);
			$card->display();
		}
		?>
	</div>
</div>