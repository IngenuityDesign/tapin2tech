<?php

  abstract class FEED {
  	protected abstract function retrieve();
  	protected abstract function setOpts();
  	protected abstract function formatResutls();


  	protected function request($isSimple = false,$url=false){
  		$ch = $this->doCurl($isSimple,$url);
  		$isJson = ($isSimple && $url !=false) ? true: false;
  		return $this->response($ch, $isJson); 
  	}
  	protected function doCurl($isSimple = false,$url=false){
  		$ch = curl_init();
		if($isSimple && $url != false){
			$this->doSimpleOpts($ch,$url);
		}else{
			$this->setOpts($ch);
		}
		return $ch;
  	}
  	protected function doSimpleOpts($ch,$url){
  		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	}
  	protected function response($ch, $isJson = true){
  		$resp = curl_exec($ch);
		curl_close($ch);
		return json_decode($resp);
  	}
  	protected function getOAuthHeader($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET){
  		$signature = $this->getOAuthSignature($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET);
  		$oauth_header = '';
		$oauth_header .= 'oauth_consumer_key="'. $KEY .'", ';
		$oauth_header .= 'oauth_nonce="' . time() . '", ';
		$oauth_header .= 'oauth_signature="' . $signature . '", ';
		$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
		$oauth_header .= 'oauth_timestamp="' . time() . '", ';
		$oauth_header .= 'oauth_token="'. $TOKEN .'", ';
		$oauth_header .= 'oauth_version="1.0", ';
		return array["Authorization: Oauth {$oauth_header}", 'Expect:'];
  	}

  	protected function getOAuthSignature($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET){
  		$oauth_hash = '';
		$oauth_hash .= 'oauth_consumer_key='.$KEY.'&';
		$oauth_hash .= 'oauth_nonce=' . time() . '&';
		$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
		$oauth_hash .= 'oauth_timestamp=' . time() . '&';
		$oauth_hash .= 'oauth_token='.$TOKEN.'&';
		$oauth_hash .= 'oauth_version=1.0';
		$base = '';
		$base .= 'GET';
		$base .= '&';
		$base .= rawurlencode($URL);
		$base .= '&';
		$base .= rawurlencode($oauth_hash);
		$key = '';
		$key .= rawurlencode($KEY_SECRET);
		$key .= '&';
		$key .= rawurlencode($TOKEN_SECRET);
		$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
		$signature = rawurlencode($signature);
		return $signature;
  	}

  }

  class TW_FEED extends FEED {
  	protected $searches     = ['%23LifeAtVisa','%23VisaNews','%40visa'];
  	protected $API_KEY      = "jOVmbimft5wX6lMU7KsH5S5JS";
  	protected $API_SECRET   = "xq740a0LtD98S0KV9AfqaOrt4avNSUdpTaKDM1w3EvuNEh95dJ";
  	protected $ID		    = "3438087887";
  	protected $TOKEN 	    = "3438087887-JSCRMIVejFBWJBaaCSkFEriajRLBsnA8IzdSZNm";
  	protected $TOKEN_SECRET = "orc8HKe53kVODPEqMC9nx7YrLhC4sNL3nXkTNIshDUZyW";
  	protected $BASE_URL		= "https://api.twitter.com/1.1/search/tweets.json";
  	protected $header 		= false;

  	public function formatResults(){
  		$res = $this->retrieve();
  		$tweets = [];
  		foreach($res->statuses as $tweet){
  			$item = [];
  			$item['body'] 	  = $tweet->text;
  			$item['name']     = $tweet->user->name;
  			$item['username'] = $tweet->user->screen_name;
  			$item['userimg']  = $tweet->user->profile_image_url;
  			$item['profilelink'] = "<a href='https://twitter.com/{$item[$username]}>@{$item[$username]}</a>";
  			$tweets[] = $item;
  		}
  		return $tweets;
  	}

  	public function retrieve(){
  		return parent::request();
  	}

  	public function getHeader(){
  		return parent::getOAuthHeader($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET);
  	}

  	public function buildURI(){
  		return $BASE_URL . 'q=' . implode('+OR+',$searches) . '&result_type=mixed&count=20';
  	}

  	protected function setOpts($ch){
  		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $this->buildURI());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	}
  	
  }

  class FB_FEED extends FEED {
  	protected $search       = '%23EVERYWHERE';
  	protected $API_KEY      = "jOVmbimft5wX6lMU7KsH5S5JS";
  	protected $API_SECRET   = "cce55079576f3cbe96828c2d8ffb3ae6";
  	protected $ID		    = "1614550268832726";
  	protected $TOKEN 	    = '1614550268832726|T_POkQw_mGEsIv5GG4SSAkjsBZM';
  	protected $BASE_URI		= "https://graph.facebook.com/134335680053868/feed";

  	public function formatResults(){
  		$res = $this->retrieve();
  		$tweets = [];
  		foreach($res->statuses as $tweet){
  			$item = [];
  			$item['body'] 	  = $tweet->text;
  			$item['name']     = $tweet->user->name;
  			$item['username'] = $tweet->user->screen_name;
  			$item['userimg']  = $tweet->user->profile_image_url;
  			$item['profilelink'] = "<a href='https://twitter.com/{$item[$username]}>@{$item[$username]}</a>";
  			$tweets[] = $item;
  		}
  		return $tweets;
  	}

  	public function retrieve(){
  		return parent::request();
  	}

  	public function getHeader(){
  		return parent::getOAuthHeader($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET);
  	}
  	protected function getToken(){
  		$url = "https://graph.facebook.com/oauth/access_token?client_id={$ID}&client_secret={$API_SECRET}&grant_type=client_credentials";
  		$resp = $this->request(true,$url);
  		$this->TOKEN = str_replace('access_token=','',$resp);
  		return $this->TOKEN;
  	}

  	public function buildURI(){
  		return $BASE_URL . '?hashtag='. $search .'&access_token=' . $TOKEN;
  	}

  	protected function setOpts($ch){
  		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
		curl_setopt($ch, CURLOPT_HEADER, true;
		curl_setopt($ch, CURLOPT_URL, $this->buildURI());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	}
  	
  }


