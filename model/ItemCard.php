<?php

class ItemCard {

	protected $data;
	protected $defs;
	protected $info;
	protected $lang;

	public function __construct($data, $defs, $lang) {
		$this->data = $data;
		$this->defs = $defs;
		$this->info = $defs['items'][(string)$this->data->itemHash];
		$this->lang = $lang;
	}

	private function display_weapon() { 
		//var_dump($this->data);
		$damageIcon = ""; $damageClass = "";
		switch($this->data->damageType) {
			case 2: $damageIcon = "<div class='element-image'></div>";
					$damageClass = " class='element-arc'"; 
					break;
			case 3: $damageIcon = "<div class='element-image'></div>";
					$damageClass = " class='element-fire'"; 
					break;
			case 4: $damageIcon = "<div class='element-image'></div>";
					$damageClass = " class='element-void'"; 
					break;
		} ?>
		<h2<?=$damageClass?>><?=$damageIcon.$this->data->primaryStat->value?>
			<span><?=$this->defs['stats']['368428387']['statName']?></span>
		</h2>
		<i><?=@$this->info['itemDescription']?></i>
		<hr/>
		<?php $count = 0;
		foreach($this->info['stats'] as $hash => $stat) { 
			if ($hash != "368428387" && $hash != "3555269338" && $hash != "3871231066" &&
				$hash != "1931675084" && $hash != "943549884" && $hash != "1345609583" &&
				$hash != "2715839340" && $count < 5) { 
				$maximum = $stat['maximum'] > 0 ? $stat['maximum'] : $stat['value']; ?>
		<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$hash?>">
			<span><?=$this->defs['stats'][(string)$hash]['statName']?></span>
			<div class="stat-bar">
				<div style="width:<?=$maximum?>%">
					<div style="width:<?=@($stat['value']/$maximum)*100?>%"></div>
				</div>
			</div>
			<small><?=$stat['value']?> / <?=$maximum?></small>
		</div>
		<?php $count++;
			} 
		}
		$stat = $this->info['stats']['3871231066']; ?>
		<div class="stat">
			<span><?=$this->defs['stats']['3871231066']['statName']?></span>
			<small class="stat-text"><?=$stat['value']?> / <?=$stat['maximum']>0?$stat['maximum']:$stat['value']?></small>
		</div>
		<?php
	}

	private function display_armor() { 
		if (count($this->data->stats) > 0) { 
			if ($this->data->stats[0]->value > 0) { ?>
		<h3 class="prestige pull-right"><?=$this->data->stats[0]->value?></h3>
		<?php } ?>
		<h2><?=$this->data->primaryStat->value?>
			<span><?=$this->defs['stats']['3897883278']['statName']?></span>
		</h2>
		<?php } ?>
		<i><?=@$this->info['itemDescription']?></i>
		<?php if (count($this->data->stats) > 0) { 
			$stats = array();
			foreach($this->data->stats as $index => $stat) {
				$hash = $stat->statHash;
				if (($hash == "1735777505" || $hash == "144602215" || $hash == "4244567218") && $stat->value > 0) {
					array_push($stats, $hash);
				}
			} 
			if (!empty($stats)) { ?>
			<hr/>
			<?php foreach($this->data->stats as $index => $stat) {
					$hash = $stat->statHash;
					if (in_array($hash, $stats)) { ?>
					<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$hash?>">
						<img src="http://www.bungie.net<?=$this->defs['stats'][(string)$hash]['icon']?>"/>
						<span><?=$this->defs['stats'][(string)$hash]['statName']?></span>
						<small class="stat-text"><?=$stat->value?></small>
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
		<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$hash?>">
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
		$shaderClass = ($shader && (string)$this->data->itemHash !== "4248210736") ? " shader" : "";
?>
<div id="<?=(string)$this->data->itemHash."-".(string)$this->data->itemInstanceId?>" class="item tier-<?=$tier?> card-popup">
	<div class="item-data<?=$shaderClass?>" <?=$emblem?"style=\"background-image:url(http://www.bungie.net".$this->info['secondaryIcon'].");background-size:cover;background-repeat:no-repeat;background-position:top left\"":""?>>
		<?php $url = $shader ? $GLOBALS['config']->site_root."/image/50/0/0/" : "http://"; ?>
		<img src="<?=$url?>www.bungie.net<?=$this->info['icon']?>" />
		<div class="item-name"><span><?=$this->info['itemName']?></span></div>
		<small class="dark"><?=$this->info['tierTypeName']?></small>
		<small class="type" style="display: none;"><?=$this->info['itemTypeName']?></small>
	</div>
	<?php if ($this->data->isGridComplete) { ?>
	<div class="upgrade" title="<?=Language::get($this->lang, "info_completed")?>"><img src="<?=$GLOBALS['config']->site_root?>/img/light.png"/></div>
	<?php } ?>
</div>
<div class="item-card tier-<?=$tier?>-dark" style="display:none<?=$nocard?";visibility:hidden;height:0;padding:0;margin:0":""?>">
	<?php if (ItemTypes::get((string)$this->data->itemHash, "playstation")) { ?>
	<i class="exclusive"><?=Language::get($this->lang, "playstation_exclusive")?></i>
	<?php } else if (ItemTypes::get((string)$this->data->itemHash, "vault_of_glass")) { ?>
	<i class="exclusive"><?=Language::get($this->lang, "vog_exclusive")?></i>
	<?php } else if (ItemTypes::get((string)$this->data->itemHash, "crotas_end")) { ?>
	<i class="exclusive"><?=Language::get($this->lang, "crota_exclusive")?></i>
	<?php } else if (ItemTypes::get((string)$this->data->itemHash, "iron_banner")) { ?>
	<i class="exclusive"><?=Language::get($this->lang, "iron_exclusive")?></i>
	<?php }
	switch($this->info['itemType']) {
		case 2: $this->display_armor(); break;
		case 3: $this->display_weapon(); break;
		case 0:
		default: $this->display_other(); break;
	}
	if (count($this->data->perks) > 0) { ?>
	<hr/>
	<?php foreach($this->data->perks as $index => $perk) { 
		$perk_enabled = $perk->isActive ? "" : " style=\"opacity: 0.2;\""; ?>
	<div class="perk row" <?=$perk_enabled?>id="<?=(string)$this->data->itemHash."-".(string)$perk->perkHash?>">
		<img class="col-xs-2" src="http://www.bungie.net<?=$perk->iconPath?>"/>
		<div class="col-xs-10">
			<span><?=$this->defs['perks'][(string)$perk->perkHash]['displayName']?></span><br/>
			<small><?=$this->defs['perks'][(string)$perk->perkHash]['displayDescription']?></small>
		</div>
	</div>
	<?php }
	} ?>
</div>
<?php }

}

class ItemTypes {

	public static $exclusives = array(
		"playstation" => array(
			"706456945", "537606595", "3140383648", "1373365671",		// Titan set 1
			"537606594", "1373365670", "3140383649", "706456944",		// Titan set 2
			"261026954", "251097816", "6252345", "4197359024",			// Warlock set 1
			"261026955", "6252344", "251097817", "4197359025",			// Warlock set 2
			"1380667989", "2956790199", "1328380235", "4279284444",	// Hunter set 1
			"2956790198", "1380667988", "1328380234", "4279284445",	// Hunter set 2
			"526316388", "526316389", "526316395",									// Ships
			"3164616407", "119482466","2344494718",									// Exotics
		),
		"vault_of_glass" => array(
			"2147998057", "3367833896", "3851493600", "2504856474",	// Titan set 1
			"2147998056", "3367833897", "3851493601", "2504856475",	// Titan set 2
			"2486746566", "4079606241", "1883484055", "3267664569",	// Warlock set 1
			"2486746567", "4079606240", "1883484054", "3267664568",	// Warlock set 2
			"1096028869", "3833808556", "1835128980", "1698410142",	// Hunter set 1
			"1096028868", "3833808557", "1835128981", "1698410143",	// Hunter set 2
			"774963973", "991704636", "2237496545",									// Class items
			"3074713346", "2149012811", "1603229152", "3892023023",	// Primary weapons
			"892741686", "1267053937", "3695068318",								// Special weapons
			"3807770941", "152628833",															// Heavy weapons
			"407626698", 																						// Shaders
			"671526061", "671526060", 															// Ships
			"1202967480",																						// Sparrows
			"346443849",																						// Exotics
		),
		"crotas_end" => array(
			"1898281764", "2450884227", "1462595581", "3786747679",	// Titan set
			"2477121987", "3009953622", "3148626578", "3549968172",	// Warlock set
			"1311326450", "1261228341", "1736102875", "186143053",	// Hunter set
			"1349707258", "2339580799", "4253790216",								// Class items
			"868574327", "4144666151", "437329200", "4252504452",		// Primary weapons
			"560601823", "1267147308", "3615265777",								// Special weapons
			"2361858758", "788203480",															// Heavy weapons
			"3269301481", 																					// Emblems
			"1906496743", "1906496742",															// Shaders
			"3458901841", "3458901840", 														// Ships
			"845577225",																						// Sparrows
			"2809229973",																						// Exotics
		),
		"iron_banner" => array(
			"1556318808", "1448055471", "2020019241", "1846030075",	// Titan set 1
			"3451716861", "3275079860", "1914248812", "2559980950",	// Titan set 2
			"1556318809", "1448055470", "2020019240", "1846030074",	// Titan set 3
			"2898884243", "1571566214", "391890850", "2902070748",	// Warlock set 1
			"1737847390", "925496553", "541785999", "1157862961",		// Warlock set 2
			"2898884242", "1571566215", "391890851", "2902070749",	// Warlock set 3
			"3470167972", "2413349891", "3034481789", "1063666591",	// Hunter set 1
			"2452629279", "3477470986", "2810850918", "2369983328",	// Hunter set 2
			"3470167973", "2413349890", "3034481788", "1063666590",	// Hunter set 3
			"3161248318", "1312172922", "2314015087",								// Class items set 1
			"189873545", "4114288667", "2239662500",								// Class items set 2
			"1998842327", "2775854838", "2135112796", "2266591883",	// Primary weapons
			"367695658", "160095218", "1221909933",									// Special weapons
			"2853794413", "805224273",															// Heavy weapons
			"2326927634", "2326927635",															// Emblems
			"3367786034", "3367786035",															// Shaders
			"2695084041",																						// Ships
		)
	);

	public static function get($hash, $type) {
		if (array_key_exists($type, self::$exclusives)) {
			if (in_array($hash, self::$exclusives[$type])) {
				return true;
			}
		}
		return false;
	}

}