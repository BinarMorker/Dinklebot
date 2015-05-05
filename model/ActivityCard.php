<?php

class ActivityCard {

	protected $defs;
	protected $act;
	protected $lang;

	public function __construct($act, $defs, $lang) {
		$this->act = $act;
		$this->defs = $defs;
		$this->lang = $lang;
	}

	public function display($displayLevel = true) { 
		$hash = (string)$this->act->activityHash;
		$destination = $this->defs['activities'][$hash]['destinationHash'];
		?>
		<div class="item subclass activity-popup" id="activity-<?=$hash?>">
			<div class="item-data">
				<img src="<?=Cache::base64Convert($GLOBALS['config']->site_root."/util/SimpleImage.php?size=50&url=http://www.bungie.net".$this->defs['activities'][(string)$hash]['icon'], false)?>" />
				<div class="activity-name"><span><?=$this->defs['activities'][(string)$hash]['activityName']?></span></div>
				<?php if ($displayLevel) { ?><small class="dark"><?=$this->defs['activities'][(string)$hash]['activityLevel']?></small><?php } ?>
				<small class="type" style="display: none;"><?=$this->defs['destinations'][(string)$destination]['destinationName']?></small>
			</div>
			<div class="level"><h3><?=$this->act->values->activityCompletions->basic->value?></h3></div>
		</div>
		<div class="item-card subclass-dark" style="display:none">
			<span><?=$this->defs['activities'][(string)$hash]['activityDescription']?></span>
			<?php if (!empty($this->defs['activities'][(string)$hash]['skulls'])) { ?>
			<hr/><h4><?=Language::get($this->lang, "info_modifiers")?>: </h4>
			<?php } ?>
			<?php foreach($this->defs['activities'][(string)$hash]['skulls'] as $skull) { ?>
			<div class="perk row">
				<img class="col-xs-2" src="<?=Cache::base64Convert($GLOBALS['config']->site_root."/util/SimpleImage.php?size=50&url=http://www.bungie.net".$skull['icon'])?>"/>
				<div class="col-xs-10">
					<span><?=$skull['displayName']?></span><br/>
					<small><?=$skull['description']?></small>
				</div>
			</div>
			<?php } if (!empty($this->defs['activities'][(string)$hash]['rewards'])) { ?>
			<hr/><h4><?=Language::get($this->lang, "info_rewards")?>: </h4>
			<?php 
				$rew = array();
			} ?>
			<?php foreach($this->defs['activities'][(string)$hash]['rewards'] as $reward) { 
				if (!in_array($reward['rewardItems'][0]['itemHash'], $rew)) {
					array_push($rew, $reward['rewardItems'][0]['itemHash']);
					$item = $this->defs['items'][(string)$reward['rewardItems'][0]['itemHash']]; ?>
					<div class="reward">
						<img src="<?=Cache::base64Convert("http://www.bungie.net".$item['icon'])?>"/>
						<small><?=$item['itemName']?></small>
						<?php if ($reward['rewardItems'][0]['value'] != 0) { ?><small> x <?=$reward['rewardItems'][0]['value']?></small><?php } ?>
					</div>
			<?php } } ?>
		</div>
	<?php }

}

class ActivityType {

	public static $social = array(
		"1589650888" => "Social",
	);

	public static $patrol = array(
		"3497767639" => "Patrol",
	);

	public static $story = array(
		"147238405" => "Featured",
		"1686739444" => "Story",
		"1299744814" => "Cinematic",
		"1801258597" => "Quest",
	);

	public static $pvp = array(
		"3614615911" => "Clash",
		"3846426416" => "Control",
		"3695721985" => "Rumble",
		"3990775146" => "Skirmish",
		"2127351241" => "Salvage",
	);

	public static $strike = array(
		"2889152536" => "Playlist",
		"575572995" => "Nightfall",
		"4164571395" => "Weekly",
		"4110605575" => "Strike",
	);

	public static $raid = array(
		"2043403989" => "Raid",
		"837773392" => "Moon",
	);

}