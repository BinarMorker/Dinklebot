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

	public function display() { ?>
		<div class="medal medal-tooltip" id="<?=(string)$this->data->statId?>" title="<h3><?=$this->defs['statName']?></h3><i><?=$this->defs['statDescription']?></i>">
			<img src="<?=Cache::base64Convert("http://www.bungie.net".$this->defs['iconImage'])?>"/>
			<div class="value"><span><?=$this->data->basic->value?></span></div>
		</div>
	<?php }

}