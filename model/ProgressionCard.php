<?php
class ProgressionCard {

	private $data;
	private $defs;
	private $lang;

	public function __construct($data, $defs, $lang) {
		$this->data = $data;
		$this->defs = $defs;
		$this->lang = $lang;
	}

	public function display() {
		if ($this->data->progressionHash == "594203991") {
			$icon = Cache::base64Convert("http://www.bungie.net".$this->defs['2161005788']['icon']);
			$percent = $this->data->level * 20;
			$count = $this->data->level;
			$max = 5;
		} elseif ($this->data->progressionHash == "2033897742") {
			$icon = Cache::base64Convert("http://www.bungie.net".$this->defs['3233510749']['icon']);
			$percent = $this->data->level;
			$count = $this->data->level;
			$max = 100;
		} elseif ($this->data->progressionHash == "2033897755") {
			$icon = Cache::base64Convert("http://www.bungie.net".$this->defs['1357277120']['icon']);
			$percent = $this->data->level;
			$count = $this->data->level;
			$max = 100;
		} else {
			$icon = Cache::base64Convert("http://www.bungie.net".$this->defs[(string)$this->data->progressionHash]['icon']);
			$percent = @($this->data->progressToNextLevel / $this->data->nextLevelAt) * 100;
			$count = $this->data->progressToNextLevel;
			$max = $this->data->nextLevelAt;
		} ?>
		<div class="card-bg progress-card" id="<?=(string)$this->data->progressionHash?>">
			<div class="progress-data">
				<img src="<?=$icon?>"/>
				<div class="progress-info">
					<div class="progress-bar">
						<div style="width:<?=$percent?>%"></div>
					</div>
					<div class="progress-name">
						<span><?=Language::get($this->lang, $this->defs[(string)$this->data->progressionHash]['name'])?></span>
					</div>
				</div>
				<small class="more" style="display: none;"><?=$count?> / <?=$max?></small>
			</div>
			<div class="level"><h3><?=$this->data->level?></h3></div>
		</div>
		<?php
	}

}