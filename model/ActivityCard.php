<?php

class ActivityCard {

	private $hash;
	private $defs;
	private $data;
	private $date;
	private $time;
	private $act;
	private $lang;
	public $completed;

	public function __construct($hash, $defs, $data, $date, $time, $act, $lang) {
		$this->hash = $hash;
		$this->defs = $defs;
		$this->data = $data;
		$this->date = $date;
		$this->time = $time;
		$this->act = $act;
		$this->lang = $lang;

		$this->completed = false;
		if (!empty((array)$this->data)) {
			$date = date(strtotime($this->date));
			$time = strtotime("-".(string)$this->time, $date);
			foreach ($this->data->activities as $activity) {
				if ((string)$activity->activityDetails->referenceId == (string)$this->hash && $activity->values->completed->basic->value == 1) {
					if (date($time) < date(strtotime($activity->period)) && date(strtotime($activity->period)) < date(strtotime($this->date))) {
						$this->completed = true;
						break;
					}
				}
			}
		}
	}

	public function display($displayLevel = true) { 
		$destination = $this->act['destinationHash'];
		?>
		<div class="item subclass activity-popup" id="activity-<?=$this->hash?>">
			<div class="item-data">
				<img src="<?=Cache::base64Convert("http://www.bungie.net".$this->act['icon'], false)?>" />
				<div class="activity-name"><span><?=$this->act['activityName']?></span></div>
				<?php if ($displayLevel) { ?><small class="dark"><?=$this->act['activityLevel']?></small><?php } ?>
				<small class="type" style="display: none;"><?=$this->defs['destinations'][(string)$destination]['destinationName']?></small>
			</div>
			<?php if ($this->completed) { ?>
			<div class="upgrade" title="<?=Language::get($this->lang, "info_completed")?>"><img src="//mastodon.tk/img/light.png"/></div>
			<?php } ?>
		</div>
		<div class="item-card subclass-dark" style="display:none">
			<i class="exclusive">
				<?=Language::get($this->lang, "info_ends")?><time class="timeago" datetime="<?=date("c", strtotime($this->date))?>"></time><?=Language::get($this->lang, "info_ends_f")?>
			</i>
			<span><?=$this->act['activityDescription']?></span>
			<?php if (!empty($this->act['skulls'])) { ?>
			<hr/><h4><?=Language::get($this->lang, "info_modifiers")?></h4>
			<?php } ?>
			<?php foreach($this->act['skulls'] as $skull) { ?>
			<div class="perk row">
				<img class="col-xs-2" src="<?=Cache::base64Convert("http://www.bungie.net".$skull['icon'])?>"/>
				<div class="col-xs-10">
					<span><?=$skull['displayName']?></span><br/>
					<small><?=$skull['description']?></small>
				</div>
			</div>
			<?php } if (!empty($this->act['rewards'])) { ?>
			<hr/><h4><?=Language::get($this->lang, "info_rewards")?></h4>
			<?php 
				$rew = array();
			} ?>
			<?php foreach($this->act['rewards'] as $reward) { 
				//var_dump($reward['rewardItems'][0]);
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