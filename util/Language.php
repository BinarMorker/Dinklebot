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

	public static $translation = array();

}