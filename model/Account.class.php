<?php
class Account {
	private $membershipType;
	private $membershipId;
	private $displayName;
	private $clanName;
	private $grimoireScore;
	private $glimmer;

	private $achivements;		// Array
	private $exotics;			// Array
	private $characters;		// Array
	
	public function __contruct($data) {
		$membershipType = $data['membershipType'];
		$membershipId = $data['membershipId'];
		$displayName = $data['displayName'];
		$clanName = $data['clanName'];
		$grimoireScore = $data['grimoireScore'];
		$glimmer = $data['inventory']['currencies'][0]['value'];
	}
}