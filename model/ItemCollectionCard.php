<?php

class ItemCollectionCard extends ItemCard {

	private $normalizedValue = array(
		"attack" => 365,
		"defense" => array(
			"helmet" => 491,
			"gauntlets" => 402,
			"chest" => 536,
			"legs" => 357,
		),
		"light" => 42,
);

	private $has;
	private $nodes;

	public function __construct($data, $has, $lang) {
		$this->data = $data;
		@$this->info = json_decode(json_encode($data->data->inventoryItem), true);
		@$this->defs = json_decode(json_encode($data->definitions), true);
		@$this->nodes = $data->data->talentGrid->nodes;
		$this->has = $has;
		$this->lang = $lang;
	}

	private function display_weapon() { 
		foreach($this->defs['perks'] as $node) {
			$damageIcon = ""; $damageClass = "";
			if (array_key_exists('perkIdentifier', $node)) {
				switch($node['perkIdentifier']) {
					case 'PERK_ARC_DAMAGE': $damageIcon = "<div class='element-image'></div>";
							$damageClass = " class='element-arc'"; 
							break;
					case 'PERK_THERMAL_DAMAGE': $damageIcon = "<div class='element-image'></div>";
							$damageClass = " class='element-fire'"; 
							break;
					case 'PERK_VOID_DAMAGE': $damageIcon = "<div class='element-image'></div>";
							$damageClass = " class='element-void'"; 
							break;
				}
				if ($damageIcon != "") {
					break;
				}
			}
		} ?>
		<h2<?=$damageClass?>><?=$damageIcon.$this->normalizedValue['attack']?>
			<span><?=$this->defs['stats']['368428387']['statName']?></span>
		</h2>
		<i><?=@$this->info['itemDescription']?></i>
		<hr/>
		<?php $count = 0;
		foreach($this->info['stats'] as $hash => $stat) { 
			if ($hash != "368428387" && $hash != "3555269338" && $hash != "3871231066" &&
				$hash != "1931675084" && $hash != "943549884" && $hash != "1345609583" &&
				$hash != "2715839340" && $count < 5) { ?>
		<div class="stat" id="<?=(string)$this->info['itemHash']."-".(string)$hash?>">
			<span><?=$this->defs['stats'][(string)$hash]['statName']?></span>
			<div class="stat-bar">
				<div style="width:<?=$stat['value']?>%"></div>
			</div>
			<small><?=$stat['value']?></small>
		</div>
		<?php $count++;
			} 
		}
		$stat = $this->info['stats']['3871231066']; ?>
		<div class="stat">
			<span><?=$this->defs['stats']['3871231066']['statName']?></span>
			<small class="stat-text"><?=$stat['value']?></small>
		</div>
		<?php
	}

	private function display_armor() { 
		if (count($this->info['stats']) > 0) { 
			/*if (array_key_exists('2391494160', $this->info['stats']) && $this->info['stats']['2391494160']['value'] > 0) {*/ ?>
		<h3 class="prestige pull-right"><?=$this->normalizedValue['light']?></h3>
		<?php /*}*/ switch ($this->info['bucketTypeHash']) {
			case "3448274439": $defense = $this->normalizedValue['defense']['helmet']; break;
			case "3551918588": $defense = $this->normalizedValue['defense']['gauntlets']; break;
			case "14239492": $defense = $this->normalizedValue['defense']['chest']; break;
			case "20886954": $defense = $this->normalizedValue['defense']['legs']; break;
		}

		?>
		<h2><?=$defense?>
			<span><?=$this->defs['stats']['3897883278']['statName']?></span>
		</h2>
		<?php } ?>
		<i><?=@$this->info['itemDescription']?></i>
		<?php if (count($this->info['stats']) > 0) { 
			$stats = array();
			foreach($this->info['stats'] as $index => $stat) {
				$hash = $stat['statHash'];
				if (($hash == "1735777505" || $hash == "144602215" || $hash == "4244567218") && $stat['value'] > 0) {
					array_push($stats, $hash);
				}
			} 
			if (false && !empty($stats)) { ?>
			<hr/>
			<?php foreach($this->info['stats'] as $index => $stat) {
					$hash = $stat['statHash'];
					if (in_array($hash, $stats)) { ?>
					<div class="stat" id="<?=(string)$this->info['itemHash']."-".(string)$hash?>">
						<img src="<?=Cache::base64Convert($GLOBALS['config']->site_root."/image/20/0/0/www.bungie.net".$this->defs['stats'][(string)$hash]['icon'])?>"/>
						<span><?=$this->defs['stats'][(string)$hash]['statName']?></span>
						<small class="stat-text"><?=$stat['value']?></small>
					</div>
					<?php 
					}
				}
			}
		}
	}

	private function display_other() { 
		if ((string)$this->info['bucketTypeHash'] == "2025709351") { ?>
		<h2><?=$this->info['stats']['360359141']['value']?>
			<span><?=$this->defs['stats']['360359141']['statName']?></span>
		</h2>
		<?php } ?>
		<i><?=@$this->info['itemDescription']?></i>
		<?php if ((string)$this->info['bucketTypeHash'] == "2025709351") { 
			$hash = "1501155019";
			$stat = $this->info['stats'][(string)$hash]; ?>
		<hr/>
		<div class="stat" id="<?=(string)$this->info['itemHash']."-".(string)$hash?>">
			<span><?=$this->defs['stats'][(string)$hash]['statName']?></span>
			<div class="stat-bar">
				<div style="width:<?=@($stat['value']/10)*100?>%"></div>
			</div>
			<small><?=$stat['value']?></small>
		</div>
		<?php }
	}

	public function display() {
		switch($this->info['tierType']) {
			case 6: $tier = "exotic"; break;
			case 5: $tier = "legendary"; break;
			case 4: $tier = "rare"; break;
			case 3: $tier = "uncommon"; break;
			case 2:
			default: $tier = "common"; break;
		}

		$emblem = (string)$this->info['bucketTypeHash'] === "4274335291" ? true : false;
		$shader = (string)$this->info['bucketTypeHash'] === "2973005342" ? true : false;
		$nocard = ($emblem || $shader);
		$shaderClass = ($shader && (string)$this->info['itemHash'] !== "4248210736") ? " shader" : "";
?>
<div id="<?=(string)$this->info['itemHash']?>" class="item tier-<?=$tier?> card-popup collection-popup">
	<div class="item-data<?=$shaderClass?>" <?=$emblem?"style=\"background-image:url(http://www.bungie.net".$this->info['secondaryIcon'].");background-size:cover;background-repeat:no-repeat;background-position:top left\"":""?>>
		<?php $url = $shader ? $GLOBALS['config']->site_root."/image/50/0/0/" : "http://"; ?>
		<img src="<?=$url?>www.bungie.net<?=$this->info['icon']?>" />
		<div class="item-name"><span><?=$this->info['itemName']?></span></div>
		<small class="dark"><?=$this->info['tierTypeName']?></small>
		<small class="type" style="display: none;"><?=$this->info['itemTypeName']?></small>
	</div>
	<?php if ($this->has) { ?>
	<div class="upgrade" title="<?=Language::get($this->lang, "info_obtained")?>"><img src="<?=$GLOBALS['config']->site_root?>/img/light.png"/></div>
	<?php } ?>
</div>
<div class="item-card tier-<?=$tier?>-dark" style="display:none<?=$nocard?";visibility:hidden;height:0;padding:0;margin:0":""?>">
	<?php if (ItemTypes::get((string)$this->info['itemHash'], "playstation")) { ?>
	<i class="exclusive"><?=Language::get($this->lang, "playstation_exclusive")?></i>
	<?php }
	switch($this->info['itemType']) {
		case 2: $this->display_armor(); break;
		case 3: $this->display_weapon(); break;
		case 0:
		default: $this->display_other(); break;
	}
	if (count($this->defs['perks']) > 1) { ?>
	<hr/>
	<?php foreach($this->nodes as $perk) { 
		if (array_key_exists(0, $perk->steps[0]->perkHashes) && array_key_exists((string)$perk->steps[0]->perkHashes[0], $this->defs['perks']) && @$this->defs['perks'][(string)$perk->steps[0]->perkHashes[0]]['isDisplayable']) { ?>
	<div class="perk row" id="<?=(string)$this->info['itemHash']."-".(string)$perk->steps[0]->perkHashes[0]?>">
		<img class="col-xs-2" src="http://www.bungie.net<?=$perk->steps[0]->icon?>"/>
		<div class="col-xs-10">
			<span><?=$perk->steps[0]->nodeStepName?></span><br/>
			<small><?=$perk->steps[0]->nodeStepDescription?></small>
		</div>
	</div>
	<?php }
		}
	} ?>
</div>
<?php }

}