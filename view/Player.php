<?php include("view/ItemCard.php"); ?>
<div class='container'>
	<div class='row'>
		<div class='col-lg-12 text-center'>
<?php if ($account->clanName != "") { ?>
			<span class='player-label'><?=$membership->displayName." // ".$account->clanName." // ".
				$definitions['items'][(string)$account->inventory->currencies[0]->itemHash]['itemName'].": ".$account->inventory->currencies[0]->value?></span>
<?php } else { ?>
			<span class='player-label'><?=$membership->displayName." // ".
				$definitions['items'][(string)$account->inventory->currencies[0]->itemHash]['itemName'].": ".$account->inventory->currencies[0]->value?></span>
<?php } ?>
			<span class='information'><i>These informations refresh every hour.</i></span>
			<a class='btn btn-dark' href='//www.facebook.com/sharer/sharer.php?u=//mastodon.tk/<?=$console?>/<?=$username?>/<?=$language?>&t=<?=$membership->displayName?>' 
				onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
				target='_blank'><i class='glyphicon glyphicon-share'></i>&nbsp;Share</a>&nbsp;
			<a class='btn btn-dark' href='//mastodon.tk/<?=$console?>/<?=$username?>/<?=$language?>' target='_blank'><i class='glyphicon glyphicon-link'></i>&nbsp;Permalink</a>
		</div>
	</div>
	<hr/>
	<div id='characters' class='row text-center'>
<?php foreach ($account->characters as $key => $character) {
	$prestigeClass = $character->isPrestigeLevel ? " class='prestige'" : ""; 
	$gender = $character->characterBase->genderType ? "Female" : "Male"; ?>
		<div class='col-lg-4' id='character<?=$key?>'>
			<a href='//bungie.net/en/Legend/<?=$character->characterBase->membershipType?>/<?=$character->characterBase->membershipId?>/<?=$character->characterBase->characterId?>' 
				class='character-label' style='background:url(//bungie.net<?=$character->backgroundPath?>);background-size:cover;background-repeat:no-repeat'>
				<img src='//bungie.net<?=$character->emblemPath?>'/>
				<h2><?=$definitions['classes'][(string)$character->characterBase->classHash]['className'.$gender]?><br/>
				<small><?=$definitions['races'][(string)$character->characterBase->raceHash]['raceName'.$gender]?></small></h2>
				<h3<?=$prestigeClass?>><?=$character->characterLevel?><br/>
				<small><img src='//bungie.net/img/theme/destiny/icons/icon_grimoire_lightgray.png'><?=$account->grimoireScore?></small></h3>
				<div class='progress-bar'>
					<div<?=$prestigeClass?> style='width:<?=$character->percentToNextLevel?>%'></div>
				</div>
			</a>
<?php foreach ($character->characterBase->peerView->equipment as $index => $item) {
	$card = new ItemCard($definitions['items'][(string)$item->itemHash], $definitions['stats']); 
	echo $card->display();
} ?>
		</div>
<?php } ?>
	</div>
</div>
<?php //var_dump($definitions); ?>