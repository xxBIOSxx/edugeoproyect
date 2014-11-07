<?php



defined('_JEXEC') or die('Restricted access');



class PuntosRecaptcha {

	private $serverURLS = NULL;
	private $ReCaptchaResponse = NULL;

	public function __construct() {
		$this->serverURLS = new stdClass();
		$this->serverURLS->RECAPTCHA_API_SERVER = "http://www.google.com/recaptcha/api";
		$this->serverURLS->RECAPTCHA_API_SECURE_SERVER = "https://www.google.com/recaptcha/api";
		$this->serverURLS->RECAPTCHA_VERIFY_SERVER = "www.google.com";

		$this->ReCaptchaResponse = new stdClass();
		$this->ReCaptchaResponse->is_valid = NULL;
		$this->ReCaptchaResponse->error = NULL;

		$this->publicKey = PuntosHelper::getSettings('recaptcha_public_key');
		$this->privateKey = PuntosHelper::getSettings('recaptcha_private_key');

	}

	private function qsencode($data) {
		$req = "";
		foreach ($data as $key => $value) {
			$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}

		// Cut the last '&'
		$req = substr($req, 0, strlen($req) - 1);
		return $req;
	}

	private function httpPost($host, $path, $data, $port = 80) {

		$req = $this->qsencode($data);

		$http_request = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
		$http_request .= "Content-Length: " . strlen($req) . "\r\n";
		$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
		$http_request .= "\r\n";
		$http_request .= $req;

		$response = '';
		if (false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) )) {
			die('Could not open socket');
		}

		fwrite($fs, $http_request);

		while (!feof($fs))
			$response .= fgets($fs, 1160); 
		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);

		return $response;
	}

	public function getHtml($error = null, $use_ssl = false) {
		if ($this->publicKey == null || $this->publicKey == "") {
			die("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
		} else {
			
		}

		if ($use_ssl) {
			$server = $this->serverURLS->RECAPTCHA_API_SECURE_SERVER;
		} else {
			$server = $this->serverURLS->RECAPTCHA_API_SERVER;
		}

		$errorpart = "";
		if ($error) {
			$errorpart = "&amp;error=" . $error;
		} else {
			
		}
		return '<script type="text/javascript" src="' . $server . '/challenge?k=' . $this->publicKey . $errorpart . '"></script>
 
        <noscript>
            <iframe src="' . $server . '/noscript?k=' . $this->publicKey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>';
	}

	public function checkAnswer($challenge, $response, $extra_params = array()) {
		if ($this->privateKey == null || $this->privateKey == "") {
			die("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
		} else {
			
		}

		$remoteIp = $_SERVER['REMOTE_ADDR'];

	
		if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
			$this->ReCaptchaResponse->is_valid = false;
			$this->ReCaptchaResponse->error = "incorrect_captcha_sol";
			return $this->ReCaptchaResponse;
		}

		$response = $this->httpPost($this->serverURLS->RECAPTCHA_VERIFY_SERVER, '/recaptcha/api/verify', array(
					"privatekey" => $this->privateKey,
					"remoteip" => $remoteIp,
					"challenge" => $challenge,
					"response" => $response
						) + $extra_params
		);
		

		$answers = explode("\n", $response[1]);

		if (trim($answers [0]) == "true") {
			$this->ReCaptchaResponse->is_valid = true;
		} else {
			$this->ReCaptchaResponse->is_valid = false;
			$this->ReCaptchaResponse->error = str_replace("-", "_", $answers[1]);
		}
		return $this->ReCaptchaResponse;
	}

	private function getSignupUrl($domain = null, $appname = null) {
		return "http://recaptcha.net/api/getkey?" . $this->qsencode(array("domain" => $domain, "app" => $appname));
	}

	private function aesPad($val) {
		$block_size = 16;
		$numpad = $block_size - (strlen($val) % $block_size);
		return str_pad($val, strlen($val) + $numpad, chr($numpad));
	}

}
