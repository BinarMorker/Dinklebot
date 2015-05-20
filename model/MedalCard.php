<?php
class MedalCard {

	private $data;
	private $defs;
	private $lang;

	public function __construct($data, $defs, $lang) {
		$this->data = $data;
		$this->defs = $defs;
		$this->lang = $lang;
	}

	public function display() { 
		$obtained = $this->data->basic->value > 0 ? true : false; ?>
		<div class="medal medal-tooltip" <?=$obtained?"":"style='opacity:0.2' "?>id="<?=(string)$this->data->statId?>" title="<h3><?=$this->defs['statName']?></h3><i><?=$this->defs['statDescription']?></i><h4><?=Language::get($this->lang, "medal_value")?>: <?=$this->defs['weight']?></h4>">
			<img src="<?=Cache::base64Convert($GLOBALS['config']->site_root."/image/40/0/0/www.bungie.net".$this->defs['iconImage'])?>"/>
			<div class="value"><span><?=$this->data->basic->value?></span></div>
		</div>
	<?php }

}