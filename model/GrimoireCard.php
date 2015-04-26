<?php

class GrimoireCard {

	protected $data;
	protected $defs;
	protected $lang;

	public function __construct($data, $defs, $lang) {
		$this->data = $data;
		$this->defs = $defs;
		$this->lang = $lang;
	}

	public function display() {
		$name = $this->defs['cardName'];
		$score = $this->data->score;
		@$intro = $this->defs['cardIntro'];
		$description = $this->defs['cardDescription'];
		switch($this->defs['rarity']) {
			case 3: $tier = "exotic"; break;
			case 2: $tier = "legendary"; break;
			case 1:
			default: $tier = "common"; break;
		}
		?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 grimoire">
			<div class="grimoire-info grimoire-popup tier-<?=$tier?>">
				<?php
					$path = $this->defs['normalResolution']['smallImage'];
					$params = "/util/SimpleImage.php?pos=".$path['rect']['x'].",".$path['rect']['y']."&crop=".$path['rect']['width']."x".$path['rect']['height']."&size=".$path['rect']['width']."&url=http://www.bungie.net";
					$image = $params . $path['sheetPath'];
				?>
				<img src="<?=$GLOBALS['site_root'].$image?>" />
				<div class="grimoire-name"><span><?=$name?></span></div>
				<div class="grimoire-score"><span><?=$score?> <img height="14px" src="<?=$GLOBALS['site_root']?>/img/icon_grimoire_lightgray.png" /></span></div>
			</div>
			<div class="grimoire-card subclass-dark" style="display:none">
				<?php if (isset($this->data->statisticCollection) && !empty($this->data->statisticCollection)) {
					foreach($this->data->statisticCollection as $id => $bonus) {
						$value = $bonus->displayValue;
						$title = $this->defs['statisticCollection'][$id]['statName']; ?>
				<span><?=$title?>: </span><strong><?=$value?></strong>
				<?php if (array_key_exists("rankCollection", $this->defs['statisticCollection'][$id])) {
							@$count = count($bonus->rankCollection); ?>
				<div class="row">
				<?php for($statId = 0; $statId < $count + 1 && $statId < 3; $statId++) {
							$stat = $this->defs['statisticCollection'][$id]['rankCollection'][$statId];
							$threshold = $stat['threshold'];
							if ($value > $threshold) {
								$statValue = $threshold;
							} else {
								$statValue = $value;
							}
							$points = $stat['points']; ?>
					<div class="col-xs-4 grimoire-stat">
						<div class="stat-bar">
							<div style="width:<?=@($statValue/$threshold)*100?>%"></div>
						</div>
						<?php if ($statValue == $threshold) { ?>
						<small>+ <?=$points?> <img height="10px" src="<?=$GLOBALS['site_root']?>/img/icon_grimoire_lightgray.png" /></small>
						<?php } else { ?>
						<small><?=$statValue?> / <?=$threshold?></small>
						<?php } ?>
					</div>
				<?php } ?>
				</div>
				<?php } 
				} ?>
				<hr/>
				<?php } if(!empty($intro)) { ?>
				<h4><?=$intro?></h4>
				<?php } ?>
				<p><?=$description?></p>
			</div>
		</div>
		<?php
	}

}