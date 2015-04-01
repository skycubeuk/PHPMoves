<?php

	namespace PHPMoves;

	class Moves {

		public $client_id;
		public $client_secret;
		public $api_url;
		public $redirect_url;
		public $oauth_url;

		public function __construct
		(
			$client_id, #Client ID, get this by creating an app
			$client_secret, #Client Secret, get this by creating an app
			$redirect_url, #Callback URL for getting an access token
			$oauth_url = 'https://api.moves-app.com/oauth/v1/', $api_url = 'https://api.moves-app.com/api/1.1'
		) {
			$this->api_url = $api_url;
			$this->oauth_url = $oauth_url;
			$this->client_id = $client_id;
			$this->client_secret = $client_secret;
			$this->redirect_url = $redirect_url;
		}

		#Generate an request URL
		public function requestURL() {
			$u = $this->oauth_url . 'authorize?response_type=code';
			$c = '&client_id=' . urlencode($this->client_id);
			$r = '&redirect_uri=' . urlencode($this->redirect_url);
			$s = '&scope=' . urlencode('activity location'); # Assuming we want both activity and locations
			$url = $u . $c . $s . $r;
			return $url;
		}

		#Validate access token
		public function validate_token($token) {
			$u = $this->oauth_url . 'tokeninfo?access_token=' . $token;
			$r = $this->get_http_response_code($u);
			if ($r === "200") {
				return json_decode($this->geturl($u), true);
			} else {
				return false;
			}
		}

		#Get access_token
                public function auth($request_token) {
                        return $this->auth_refresh($request_token, "authorization_code");
                }

                #Refresh access_token

                public function refresh($refresh_token) {
                        return $this->auth_refresh($refresh_token, "refresh_token");
                }

                private function auth_refresh($token, $type) {
                        $u = $this->oauth_url . "access_token";
                        $d = array('grant_type' => $type, 'client_id' => $this->client_id, 'client_secret' => $this->client_secret);
                        if ($type === "authorization_code") {
                                $d['code'] = $token;
                                $d['redirect_uri'] = $this->redirect_url;
                        } elseif ($type === "refresh_token") {
                                $d['refresh_token'] = $token;
                        }
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $u);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($d));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($ch);
                        curl_close($ch);
                        $token = json_decode($result, True);
                        return array('access_token' => $token['access_token'], 'refresh_token' => $token['refresh_token']);
                }
                        


                #Base request
		private function get($parameters, $endpoint) {
			return json_decode($this->geturl($this->api_url . $endpoint . '?' . http_build_query($parameters)), true);
		}

		#/user/profile
		public function get_profile($token) {
			$root = '/user/profile';
			return $this->get(array('access_token' => $token), $root);
		}

		#Range requests
		#/user/summary/daily
		#/user/activities/daily
		#/user/places/daily
		#/user/storyline/daily
		#date: date in yyyyMMdd or yyyy-MM-dd format
		public function get_range($access_token, $endpoint, $start, $end, $otherParameters = array()) {
			$requiredParameters = array(
				'access_token' => $access_token,
				'from'         => $start,
				'to'           => $end
			);
			$parameters = array_merge($requiredParameters, $otherParameters);
			return $this->get($parameters, $endpoint);
		}

		private function get_http_response_code($url) {
			$headers = get_headers($url);
			return substr($headers[0], 9, 3);
		}

		private function geturl($url) {
			$session = curl_init($url);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($session);
			curl_close($session);
			return $data;
		}

	}

	?>