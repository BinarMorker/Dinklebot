<?php

header('Content-Type:text/plain');

$auth = new BungieAuth();

if ($auth->do_webauth($_GET['c'], $_GET['u'], $_GET['p'])) {
	$bungled = "";
	$cookies = explode("; ", $auth->get_cookies());
	foreach ($cookies as $string) {
		$cookie = explode("=", $string);
		$value = str_replace($cookie[0], "", $string);
		if ($cookie[0] == "bungled") {
			$bungled = $value;
			break;
		}
	}
	$ch = curl_init();
	curl_setopt_array($ch, $auth->get_default_options());
	curl_setopt_array($ch, array(
		CURLOPT_URL => "https://www.bungie.net/Platform/Destiny/2/MyAccount/Vault/",
		CURLOPT_HTTPHEADER => array(
			"X-API-Key: 8147443ac3d64b238d680f89b912e285",
			"x-csrf: ".$bungled,
		),
	));
	$result = curl_exec($ch);
	self::curl_debug($ch, $result);
	echo $result;
	curl_close($ch);
} else {
	echo '{"ErrorCode":99,"ThrottleSeconds":0,"ErrorStatus":"Error","Message":"Authentication error","MessageData":{}}';
}

class BungieAuth {

	private $cookies = array();

	public function get_default_options() {
		return array(
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_COOKIEFILE => null,
			CURLOPT_COOKIE => $this->get_cookies(),
			CURLOPT_HEADER => 1,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_CAINFO => "C:\wamp\www\bungie_auth\cacert.pem",
		);
	}

	public function do_webauth($method, $username, $password) {
		$methods = array(
			'psn' => 'Psnid',
			'xbox' => 'Xuid'//'Wlid'
		);

		$dest = 'Wlid'; if (isset($methods[$method])) $dest = $methods[$method];
		$url = 'https://www.bungie.net/en/User/SignOut/';

		// Logout
		$ch = curl_init();
		curl_setopt_array($ch, $this->get_default_options());
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
		));
		$result = curl_exec($ch);
		self::curl_debug($ch, $result);
		$this->save_cookies($result);
		$redirect_url = curl_getinfo($ch)['redirect_url'];
		curl_close($ch);

		if (!empty($redirect_url)) {
			$ch = curl_init();
			curl_setopt_array($ch, $this->get_default_options());
			curl_setopt_array($ch, array(
				CURLOPT_URL => $redirect_url,
			));
			$result = curl_exec($ch);
			self::curl_debug($ch, $result);
			
			$this->save_cookies($result);
			$redirect_url = curl_getinfo($ch)['redirect_url'];
			curl_close($ch);

			$ch = curl_init();
			curl_setopt_array($ch, $this->get_default_options());
			curl_setopt_array($ch, array(
				CURLOPT_URL => $redirect_url,
			));
			$result = curl_exec($ch);
			self::curl_debug($ch, $result);
			$this->save_cookies($result);
			//$redirect_url = curl_getinfo($ch)['redirect_url'];
			curl_close($ch);
		}

		$url = 'https://www.bungie.net/en/User/SignIn/'.$dest;

		// Get Third Party Authorization URL
		$ch = curl_init();
		curl_setopt_array($ch, $this->get_default_options());
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
		));
		$result = curl_exec($ch);
		self::curl_debug($ch, $result);
		$this->save_cookies($result);
		$redirect_url = curl_getinfo($ch)['redirect_url'];
		curl_close($ch);

		// Bungie Cookies are still valid
		if (!$redirect_url) return true;

		// Try to authenticate with Third Party
		$ch = curl_init();
		curl_setopt_array($ch, $this->get_default_options());
		curl_setopt_array($ch, array(
			CURLOPT_URL => $redirect_url,
		));
		$auth_result = curl_exec($ch);
		self::curl_debug($ch, $auth_result);
		$auth_info = curl_getinfo($ch);
		$auth_url = $auth_info['redirect_url'];

		// Normally authentication will produce a 302 Redirect, but Xbox is special...
		if ($auth_info['http_code'] == 200) $auth_url = $auth_info['url'];

		curl_close($ch);

		// No valid cookies
		if (strpos($auth_url, $url.'?code') !== 0) {
			$result = false;
			switch($method) {
				case 'psn':
					$login_url = 'https://auth.api.sonyentertainmentnetwork.com/login.do';
					$params = explode('?', $auth_url);
					$params = base64_encode($params[1]);

					var_dump($params);

					// Login to PSN
					$ch = curl_init();
					curl_setopt_array($ch, $this->get_default_options());
					curl_setopt_array($ch, array(
						CURLOPT_URL => $login_url,
						CURLOPT_POST => 3,
						CURLOPT_POSTFIELDS => http_build_query(array(
							'params' => $params,
							'j_username' => $username,
							'j_password' => $password,
							'rememberSignIn' => 'off' // Remember signin
						)),
					));
					$result = curl_exec($ch);
					self::curl_debug($ch, $result);
					$this->save_cookies($result);
					$redirect_url = curl_getinfo($ch)['redirect_url'];
					curl_close($ch);

					if (strpos($redirect_url, 'authentication_error') !== false) return false;

					// Authenticate with Bungie
					$ch = curl_init();
					curl_setopt_array($ch, $this->get_default_options());
					curl_setopt_array($ch, array(
						CURLOPT_URL => $redirect_url,
						CURLOPT_FOLLOWLOCATION => true
					));
					$result = curl_exec($ch);
					self::curl_debug($ch, $result);
					$this->save_cookies($result);
					$result = curl_getinfo($ch);
					curl_close($ch);
					break;
				case 'xbox':
					$login_url = 'https://login.live.com/ppsecure/post.srf?'.substr($redirect_url, strpos($redirect_url, '?')+1);
					preg_match('/id\="i0327" value\="(.*?)"\//', $auth_result, $ppft);

					if (count($ppft) == 2) {
						$ch = curl_init();
						curl_setopt_array($ch, $this->get_default_options());
						curl_setopt_array($ch, array(
							CURLOPT_URL => $login_url,
							CURLOPT_POST => 3,
							CURLOPT_POSTFIELDS => http_build_query(array(
								'login' => $username,
								'passwd' => $password,
								'KMSI' => 0, // Stay signed in
								'PPFT' => $ppft[1]
							)),
							CURLOPT_FOLLOWLOCATION => true
						));
						$auth_result = curl_exec($ch);
						self::curl_debug($ch, $auth_result);
						$auth_url = curl_getinfo($ch)['url'];
						curl_close($ch);
						if (strpos($auth_url, $url.'?code') === 0) {
							return true;
						}
					}
					return false;
					break;
			}
			$result_url = $result['url'];
			if ($result['http_code'] == 302) $result_url = $result['redirect_url'];

			// Account has not been registered with Bungie
			if (strpos($result_url, '/Register') !== false) return false;

			// Login successful, "bungleatk" should be set
			// Facebook/PSN should return with ?code=
			// Xbox should have ?wa=wsignin1.0
			return strpos($result_url, $url) === 0;
		}
		// Valid Third Party Cookies, re-authenticating Bungie Login
		$ch = curl_init();
		curl_setopt_array($ch, $this->get_default_options());
		curl_setopt_array($ch, array(
			CURLOPT_URL => $auth_url,
		));
		$result = curl_exec($ch);
		self::curl_debug($ch, $result);
		$this->save_cookies($result);
		curl_close($ch);
		return true;
	}

	private function save_cookies($header) {
		preg_match_all('|Set-Cookie: (.*);|U', $header, $matches);  
		foreach ($matches[1] as $match) {
			$cookie = explode("=", $match);
			$value = str_replace($cookie[0], "", $match);
			$this->cookies[$cookie[0]] = $value;
		}
	}

	public function get_cookies() {
		$list = array();
		foreach ($this->cookies as $name => $value) {
			$cookie = $name . "=" . $value;
			array_push($list, $cookie);
		}
		return implode("; ", $list);
	}

	public static function curl_debug($ch, $result) {
		echo "\r\n";
		var_dump(curl_error($ch));
		var_dump(curl_getinfo($ch));
		var_dump($result);
	}

}