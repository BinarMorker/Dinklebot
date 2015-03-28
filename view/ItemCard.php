<?php
class ItemCard {

	private $data;
	private $stats;

	public function __construct($data, $stats) {
		$this->data = $data;
		$this->stats = $stats;
	}

	private function display_weapon() {
		
	}

	private function display_armor() {

	}

	private function display_other() {
?>
<i><?=$this->data['itemDescription']?></i>
<?php foreach($this->data['stats'] as $stat) { ?>
<div class="stat">
	<span><?=$this->stats[(string)$stat['statHash']]['statName']?></span>
	<div class="stat-bar">
		<div style="width:<?=@($stat['value']/$stat['maximum'])*100?>%"></div>
	</div>
	<small><?=$stat['value']?> / <?=$stat['maximum']?></small>
</div>
<?php }
		//var_dump($this->data);
	}

	public function display() {
		switch($this->data['tierType']) {
			case 6: $tier = "exotic"; break;
			case 5: $tier = "legendary"; break;
			case 4: $tier = "rare"; break;
			case 3: $tier = "uncommon"; break;
			case 2:
			default: $tier = "common"; break;
		}
?>
<div class="item tier-<?=$tier?> card-popup">
	<img src="//bungie.net<?=$this->data['icon']?>" />
	<div class="item-name"><span><?=$this->data['itemName']?></span></div>
	<small class="dark"><?=$this->data['tierTypeName']?></small>
	<small class="type" style="display: none;"><?=$this->data['itemTypeName']?></small>
</div>
<div class="item-card tier-<?=$tier?>-dark" style="display: none;">
	<?php /*switch($this->data['itemType']) {
		case 2: $this->display_armor(); break;
		case 3: $this->display_weapon(); break;
		case 0:
		default: */$this->display_other();/* break;
	}*/ ?>
</div>
<?php
	}

}