<?php
class Cache {

	private static $full_caching = true;

	private $cachefile;
	private $enabled = true;
	private $cache_time = 3600;

	public function __construct() {
		$url = $_SERVER["REQUEST_URI"];
		$num = count(explode('/', $_SERVER["SCRIPT_NAME"])) - 1;
		$break = explode('/', $url);
		$break = array_slice($break, $num, count($break) - $num);
		$file = "";
		foreach($break as $item) {
			if ($item != null && $item != "" && !empty($item) && $item != "refresh") {
				$file .= "_".strtolower($item);
			}
		}
		$this->cachefile = 'cache/cached'.$file.'.html';
	}

	public function start() {
		if ($this->enabled) {
			// Serve from the cache if it is younger than cache time
			if (file_exists($this->cachefile) && time() - $this->cache_time < filemtime($this->cachefile)) {
			    echo "<!-- Cached copy, generated ".date('H:i', filemtime($this->cachefile))." -->\n";
			    include($this->cachefile);
			    exit;
			}
			ob_start(); // Start the output buffer
		}
	}

	public function disable() {
		$this->enabled = false;
	}

	public function remove() {
		if (file_exists($this->cachefile)) {
			unlink($this->cachefile);
		}
	}

	public function close() {
		if ($this->enabled) {
			// Cache the contents to a file
			$cached = fopen($this->cachefile, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);
			ob_end_flush(); // Send the output to the browser
		}
	}

	public static function base64Convert($path, $override = false) {
		if (self::$full_caching || $override) {
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			return 'data:image/' . $type . ';base64,' . base64_encode($data);
		} else {
			return $path;
		}
	}

}