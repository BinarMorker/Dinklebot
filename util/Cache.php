<?php
class Cache {

	private $cachefile;

	public function start() {
		$url = $_SERVER["REQUEST_URI"];
		$num = count(explode('/', $_SERVER["SCRIPT_NAME"])) - 1;
		$break = explode('/', $url);
		$break = array_slice($break, $num, count($break) - $num);
		$file = "";
		foreach($break as $item) {
			if ($item != null && $item != "" && !empty($item)) {
				$file .= "_".$item;
			}
		}
		$this->cachefile = 'cache/cached'.$file.'.html';
		$cachetime = 20;

		// Serve from the cache if it is younger than $cachetime
		if (file_exists($this->cachefile) && time() - $cachetime < filemtime($this->cachefile)) {
		    echo "<!-- Cached copy, generated ".date('H:i', filemtime($this->cachefile))." -->\n";
		    include($this->cachefile);
		    exit;
		}
		ob_start(); // Start the output buffer
	}

	public function close() {
		// Cache the contents to a file
		$cached = fopen($this->cachefile, 'w');
		fwrite($cached, ob_get_contents());
		fclose($cached);
		ob_end_flush(); // Send the output to the browser
	}

}