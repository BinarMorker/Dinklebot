<?php
class StatList {

	private $validStats = array(
		"activitiesCleared",
		"weaponKillsSuper",
		"activitiesEntered",
		"weaponKillsMelee",
		"weaponKillsGrenade",
		"abilityKills",
		"activitiesWon",
		"assists",
		"kills",
		"averageKillDistance",
		"secondsPlayed",
		"deaths",
		"averageLifespan",
		"averageScorePerKill",
		"averageScorePerLife",
		"bestSingleGameKills",
		"bestSingleGameScore",
		"dominationKills",
		"killsDeathsRatio",
		"killsDeathsAssists",
		"objectivesCompleted",
		"precisionKills",
		"resurrectionsPerformed",
		"resurrectionsReceived",
		"suicides",
		"weaponKillsAutoRifle",
		"weaponKillsFusionRifle",
		"weaponKillsHandCannon",
		"weaponKillsMachinegun",
		"weaponKillsPulseRifle",
		"weaponKillsRocketLauncher",
		"weaponKillsScoutRifle",
		"weaponKillsShotgun",
		"weaponKillsSniper",
		"weaponBestType",
		"winLossRatio",
		"defensiveKills",
		"longestKillSpree",
		"longestSingleLife",
		"mostPrecisionKills",
		"offensiveKills",
		"orbsDropped",
		"orbsGathered",
		"relicsCaptured",
		"publicEventsCompleted",
		"publicEventsJoined",
		"zonesCaptured",
		"zonesNeutralized",
		"combatRating",
	);

	private $data = array();
	private $defs;
	private $lang;

	public function __construct($data, $defs, $lang) {
		foreach($data as $id => $stat) {
			if (in_array($id, $this->validStats)) {
				$this->data[$id] = $stat;
			}
		}
		$this->defs = $defs;
		$this->lang = $lang;
	}

	public function get_data() {
		return $this->data;
	}

	public function display() { ?>
		<div class="outer">
		<?php foreach($this->data as $id => $stat) { ?>
			<div class="inner">
				<div class='filler'></div>
				<span class='name'><?=$this->defs[$id]['statName']?></span>
				<span class='value'><?=$stat->basic->displayValue?></span>
			</div>
		<?php } ?>
		</div>
	<?php }

}