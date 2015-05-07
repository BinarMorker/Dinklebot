<?php

class Language {

	private static $supported_langs = array(
		"Deutsch" => "de",
		"English" => "en",
		//"Español" => "es",
		"Français" => "fr",
		"Italiano" => "it",
		//"Português (Brasil)" => "pt-br",
		"日本語" => "ja"
	);

	public static function get_languages() {
		return self::$supported_langs;
	}

	public static function best() {
		if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			return 'en';
		}
		$languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		foreach($languages as $lang) {
			$break = explode(';', $lang);
			$lang = $break[0];
			if(in_array($lang, self::$supported_langs)) {
				return $lang;
			}
		}
		return 'en';
	}

	public static function get($language, $string) {
		if (array_key_exists($language, self::$translation) && array_key_exists($string, self::$translation[$language])) {
			return self::$translation[$language][$string];
		} elseif (array_key_exists($string, self::$translation['en'])) {
			return self::$translation['en'][$string];
		} else {
			return $string;
		}
	}

	public static function exists($language) {
		if (in_array($language, self::$supported_langs)) {
			return true;
		} else {
			return false;
		}
	}

	public static function load($language) {
		if (file_exists("lang/".$language.".lang")) {
			self::$translation[$language] = json_decode(file_get_contents("lang/".$language.".lang"), true);
			return true;
		} else {
			return false;
		}
	}

	public static function get_missing($language) {
		if (array_key_exists($language, self::$translation)) {
			$result = array();
			foreach(self::$translation['en'] as $key => $string) {
				if (!array_key_exists($key, self::$translation[$language])) {
					array_push($result, array($key => $string));
				}
			}
			return $result;
		} else {
			return null;
		}
	}

	public static $translation = array(
		"en" => array(
			"site_name" => "Dinklebot",
			"site_description" => "Your personal companion for tracking and showing your Guardians",
			"site_styled_description" => "Your <i>personal companion</i> for <strong>tracking</strong> and <strong>showing</strong> your Guardians",
			"site_username" => "Username",
			"site_footer_contact" => "For feedback, suggestions, bug reports or anything related with this website, contact us on <a href='https://www.facebook.com/dinklebotapp'>Facebook</a> or <a href='http://redd.it/33aizq'>Reddit</a>!",
			"site_footer_mention" => "is in no way affiliated with Bungie.",
			"site_footer_language" => "Language",
			"site_thankyou" => "Thank you a lot!",
			"site_thankyou_f" => "You're what makes this website awesome!",
			"category_exotic_armor" => "Exotic Armor",
			"category_exotic_weapons" => "Exotic Weapons",
			"category_vog_armor" => "Vault of Glass Armor",
			"category_vog_weapons" => "Vault of Glass Weapons",
			"category_vog_misc" => "Vault of Glass Misc",
			"category_crota_armor" => "Crota's End Armor",
			"category_crota_weapons" => "Crota's End Weapons",
			"category_crota_misc" => "Crota's End Misc",
			"category_ironbanner_armor" => "Iron Banner Armor",
			"category_ironbanner_weapons" => "Iron Banner Weapons",
			"category_ironbanner_misc" => "Iron Banner Misc",
			"info_obtained" => "Obtained",
			"info_refresh" => "This information refreshes every hour.",
			"info_ends" => "Ends ",
			"info_ends_f" => "",
			"info_modifiers" => "Modifiers",
			"info_rewards" => "Possible Rewards",
			"info_completed" => "Completed",
			"info_completed_b" => "Completed ",
			"info_completed_f" => "",
			"info_completions" => "Completions",
			"info_light_twenty" => "You must be level 20 to gain Light.",
			"info_no_pvp" => "You must participate in the Crucible at least once to obtain medals.",
			"time_played" => "Played ",
			"time_played_f" => "",
			"time_active" => "Active",
			"time_minute" => "m ",
			"time_hour" => "h ",
			"time_day" => "d ",
			"button_share" => "Share",
			"button_permalink" => "Permalink",
			"button_reload" => "Reload",
			"button_characters" => "Guardians",
			"button_grimoire" => "Grimoire",
			"button_collection" => "Collection",
			"button_show" => "Show",
			"menu_overview" => "Guardian",
			"menu_weekly" => "Periodic",
			"menu_equipment" => "Equipment",
			"menu_progression" => "Progression",
			"menu_statistics" => "Statistics",
			"menu_medals" => "Medals",
			"menu_all" => "All",
			"menu_story" => "Story",
			"menu_patrol" => "Patrol",
			"menu_strikes" => "Strike",
			"menu_crucible" => "Crucible",
			"menu_raid" => "Raid",
			"playstation_exclusive" => "Playstation Exclusive",
			"vog_exclusive" => "Vault of Glass Exclusive",
			"crota_exclusive" => "Crota's End Exclusive",
			"iron_exclusive" => "Iron Banner Exclusive",
			"character_prestige" => "Mote of Light",
			"destination_chests_cosmodrome" => "Earth Golden Chests",
			"destination_chests_mars" => "Mars Golden Chests",
			"destination_chests_moon" => "Moon Golden Chests",
			"destination_chests_venus" => "Venus Golden Chests",
			"faction_cryptarch" => "Crypto-Archeology",
			"faction_eris" => "Crota's Bane",
			"faction_event_iron_banner" => "Iron Banner",
			"faction_event_queen" => "Queen",
			"faction_fotc_vanguard" => "Vanguard Reputation",
			"faction_pvp" => "The Crucible",
			"faction_pvp_dead_orbit" => "Dead Orbit",
			"faction_pvp_future_war_cult" => "Future War Cult",
			"faction_pvp_new_monarchy" => "New Monarchy",
			"r1_s3_factions_fallen" => "House of Judgment",
			"r1_s3_factions_queen" => "Queen",
			"r1_s3_tickets.pvp.trials_of_osiris.wins" => "Trials of Osiris Wins",
			"r1_s3_tickets.pvp.trials_of_osiris.losses" => "Trials of Osiris Losses",
			"pvp_iron_banner.loss_tokens" => "Medallions of Iron",
			"pve_coins" => "Vanguard Marks",
			"pvp_coins" => "Crucible Marks",
			"weekly_pve" => "Weekly Vanguard Marks",
			"weekly_pvp" => "Weekly Crucible Marks",
			"medal_value" => "Value",
		),
	);

}