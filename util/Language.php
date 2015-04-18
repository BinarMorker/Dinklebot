<?php

class Language {

	private static $supported_langs = array(
		"Deutsch" => "de",
		"English" => "en",
		//"Español" => "es",
		"Français" => "fr",
		//"Italiano" => "it",
		//"Português (Brasil)" => "pt-br",
		"日本語" => "ja"
	);

	public static function get_languages() {
		return self::$supported_langs;
	}

	public static function best() {
		$languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		foreach($languages as $lang) {
			$lang = explode(';', $lang)[0];
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

	public static $translation = array(
		"en" => array(
			"info_refresh" => "These informations refresh every hour.",
			"info_ends" => "Ends ",
			"info_ends_f" => "",
			"info_modifiers" => "Modifiers:",
			"info_rewards" => "Possible Rewards:",
			"info_completed" => "Completed",
			"button_share" => "Share",
			"button_permalink" => "Permalink",
			"button_reload" => "Reload",
			"menu_overview" => "Overview",
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
			"never_entered_pvp" => "You must play Crucible at least once to obtain medals.",
			"playstation_exclusive" => "Playstation Exclusive",
			"vog_exclusive" => "Vault of Glass Exclusive",
			"crota_exclusive" => "Crota's End Exclusive",
			"iron_exclusive" => "Iron Banner Exclusive",
			"character_prestige" => "Mote of Light",
			"destination_chests_cosmodrome" => "",
			"destination_chests_mars" => "",
			"destination_chests_moon" => "",
			"destination_chests_venus" => "",
			"faction_cryptarch" => "Crypto-Archeology",
			"faction_eris" => "Crota's Bane",
			"faction_event_iron_banner" => "Iron Banner",
			"faction_event_queen" => "Queen",
			"faction_fotc_vanguard" => "Vanguard Reputation",
			"faction_pvp" => "The Crucible",
			"faction_pvp_dead_orbit" => "Dead Orbit",
			"faction_pvp_future_war_cult" => "Future War Cult",
			"faction_pvp_new_monarchy" => "New Monarchy",
			"pvp_iron_banner.loss_tokens" => "Medallions of Iron",
			"pve_coins" => "Vanguard Marks",
			"pvp_coins" => "Crucible Marks",
			"weekly_pve" => "Weekly Vanguard Marks",
			"weekly_pvp" => "Weekly Crucible Marks"
		),
		"fr" => array(
			"info_refresh" => "Ces informations sont mises à jour toutes les heures.",
			"info_ends" => "Termine ",
			"info_ends_f" => "",
			"button_share" => "Partager",
			"button_permalink" => "Permalien",
			"button_reload" => "Mettre à jour",
			"menu_weekly" => "Périodique",
			"menu_equipment" => "Équipement",
			"menu_progression" => "Progression",
			"menu_statistics" => "Statistiques",
			"menu_medals" => "Médailles",
			"menu_all" => "Tous",
			"menu_story" => "Histoire",
			"menu_patrol" => "Patrouille",
			"menu_strikes" => "Assaut",
			"menu_crucible" => "L'Épreuve",
			"menu_raid" => "Raid",
			"playstation_exclusive" => "Exclusivité Playstation",
			"vog_exclusive" => "Exclusivité du Caveau de Verre",
			"crota_exclusive" => "Exclusivité de La chute de Cropta",
			"iron_exclusive" => "Exclusivité de La bannière de Fer",
			"character_prestige" => "Particule de Lumière",
			"destination_chests_cosmodrome" => "",
			"destination_chests_mars" => "",
			"destination_chests_moon" => "",
			"destination_chests_venus" => "",
			"faction_cryptarch" => "Cryptoarchéologie",
			"faction_eris" => "Le Fléau de Cropta",
			"faction_event_iron_banner" => "Bannière de Fer",
			"faction_event_queen" => "Reine",
			"faction_fotc_vanguard" => "Estime de l'Avant-Garde",
			"faction_pvp" => "L'Épreuve",
			"faction_pvp_dead_orbit" => "L'Astre Mort",
			"faction_pvp_future_war_cult" => "Culte de la Guerre Future",
			"faction_pvp_new_monarchy" => "Nouvelle Monarchie",
			"pvp_iron_banner.loss_tokens" => "Médallions de Fer",
			"pve_coins" => "Estime de l'Avant-Garde",
			"pvp_coins" => "Estime de l'Épreuve",
			"weekly_pve" => "Estime de l'Avant-Garde hebdomadaire",
			"weekly_pvp" => "Estime de l'Épreuve hebdomadaire"
		),
		"de" => array(
			"info_refresh" => "Diese Informationen jede Stunde actualizieren.",
			"info_ends" => "Enden ",
			"info_ends_f" => "",
			"button_share" => "Teilen",
			"button_permalink" => "Permalink",
			"button_reload" => "Nachladen",
			"menu_weekly" => "Periodisch",
			"menu_equipment" => "Ausrüstung",
			"menu_progression" => "Fortschritt",
			"menu_statistics" => "Statistik",
			"menu_medals" => "Medaillen",
			"menu_all" => "Alle",
			"menu_story" => "Story",
			"menu_patrol" => "Patrouille",
			"menu_strikes" => "Strike",
			"menu_crucible" => "Schmelztiegel",
			"menu_raid" => "Raid",
			"playstation_exclusive" => "Playstation exklusiv",
			"vog_exclusive" => "Gläserne Kammer exklusiv",
			"crota_exclusive" => "Crota's Ende exklusiv",
			"iron_exclusive" => "Eisenbanner exklusiv",
			"character_prestige" => "Licht-Partikel",
			"destination_chests_cosmodrome" => "",
			"destination_chests_mars" => "",
			"destination_chests_moon" => "",
			"destination_chests_venus" => "",
			"faction_cryptarch" => "Kryptoarchäologie",
			"faction_eris" => "Crotas Fluch",
			"faction_event_iron_banner" => "Eisenbanner",
			"faction_event_queen" => "Königin",
			"faction_fotc_vanguard" => "Vorhut-Ruf",
			"faction_pvp" => "Der Schmelztiegel",
			"faction_pvp_dead_orbit" => "Toter Orbit",
			"faction_pvp_future_war_cult" => "Kriegskult Der Zukunft",
			"faction_pvp_new_monarchy" => "Neue Monarchie",
			"pvp_iron_banner.loss_tokens" => "Medaillons des Eisen",
			"pve_coins" => "Vorhut-Marken",
			"pvp_coins" => "Schmelztiegel-Marken",
			"weekly_pve" => "Wöchentlicher Vorhut-Marken",
			"weekly_pvp" => "Wöchentlicher Schmelztiegel-Marken"
		),
		"ja" => array(
			"info_refresh" => "そこの情報は、毎時更新します。",
			"info_ends" => "",
			"info_ends_f" => "で終了",
			"button_share" => "共有",
			"button_permalink" => "パーマリンク",
			"button_reload" => "リロード",
			"menu_weekly" => "定期的な",
			"menu_equipment" => "装備",
			"menu_progression" => "進捗",
			"menu_statistics" => "統計",
			"menu_medals" => "メダル",
			"menu_all" => "全て",
			"menu_story" => "ストーリー",
			"menu_patrol" => "パトロール",
			"menu_strikes" => "ストライク",
			"menu_crucible" => "クルーシブル",
			"menu_raid" => "レイド",
			"playstation_exclusive" => "Playstationの専属",
			"vog_exclusive" => "ガラスの間の専属",
			"crota_exclusive" => "クロタの最期の専属",
			"iron_exclusive" => "アイアンバナーの専属",
			"character_prestige" => "光のかけら",
			"destination_chests_cosmodrome" => "",
			"destination_chests_mars" => "",
			"destination_chests_moon" => "",
			"destination_chests_venus" => "",
			"faction_cryptarch" => "クリプト考古学",
			"faction_eris" => "クロタの破滅",
			"faction_event_iron_banner" => "アイアンバナー",
			"faction_event_queen" => "女王",
			"faction_fotc_vanguard" => "バンガードの評判",
			"faction_pvp" => "クルーシブル",
			"faction_pvp_dead_orbit" => "デッドオービット",
			"faction_pvp_future_war_cult" => "フューチャーウォー・カルト",
			"faction_pvp_new_monarchy" => "ニューモナーキー",
			"pvp_iron_banner.loss_tokens" => "鉄のメダリオン",
			"pve_coins" => "今週のバンガード",
			"pvp_coins" => "今週のクルーシブル",
			"weekly_pve" => "今週のバンガードの紋章",
			"weekly_pvp" => "今週のクルーシブルの紋章"
		),
	);

}