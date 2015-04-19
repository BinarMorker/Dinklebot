<?php

class SubclassCard extends ItemCard {

	private $char;

	public function __construct($data, $defs, $char, $lang) {
		parent::__construct($data, $defs, $lang);
		$this->char = $char;
	}

	public function display() { ?>
		<div id="<?=(string)$this->data->itemHash."-".(string)$this->data->itemInstanceId?>" class="item subclass card-popup">
			<div class="item-data">
				<img src="<?=Cache::base64Convert("http://www.bungie.net".$this->info['secondaryIcon'], false)?>" />
				<div class="item-name"><span><?=$this->info['itemName']?></span></div>
				<small class="type" style="display: none;"><?=$this->info['itemTypeName']?></small>
			</div>
			<?php if ($this->data->isGridComplete) { ?>
			<div class="upgrade" title="<?=Language::get($this->lang, "info_completed")?>"><img src="<?=$GLOBALS['site_root']?>/img/light.png"/></div>
			<?php } ?>
		</div>
		<div class="item-card subclass-dark" style="display:none">
			<i><?=@$this->info['itemDescription']?></i>
			<hr/>
			<?php $stat = $this->char->STAT_ARMOR; ?>
			<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$stat->statHash?>">
				<span><?=$this->defs['stats'][(string)$stat->statHash]['statName']?></span>
				<div class="stat-bar">
					<div style="width:<?=@($stat->value/10)*100?>%"></div>
				</div>
				<small><?=$stat->value?></small>
			</div>
			<?php $stat = $this->char->STAT_RECOVERY; ?>
			<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$stat->statHash?>">
				<span><?=$this->defs['stats'][(string)$stat->statHash]['statName']?></span>
				<div class="stat-bar">
					<div style="width:<?=@($stat->value/10)*100?>%"></div>
				</div>
				<small><?=$stat->value?></small>
			</div>
			<?php $stat = $this->char->STAT_AGILITY; ?>
			<div class="stat" id="<?=(string)$this->data->itemHash."-".(string)$stat->statHash?>">
				<span><?=$this->defs['stats'][(string)$stat->statHash]['statName']?></span>
				<div class="stat-bar">
					<div style="width:<?=@($stat->value/10)*100?>%"></div>
				</div>
				<small><?=$stat->value?></small>
			</div>
		</div>
		<?php }

}