<?php
  require_once(dirname(__DIR__) .'/vendor/autoload.php');
 
  abstract class FEED {
    protected abstract function retrieve();
    protected abstract function setOpts($ch);
    protected abstract function formatResults();


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
    protected function buildPostList($head,$txt,$img,$isFB = false ){
      $item  = "<div class='row'><div class='col-xs-2 col-md-2'><img src='{$img}'></div>";
      $item .= "<div class='col-md-10 col-xs-10'>";
      if($isFB) {
        $item .= "<div class='col-md-12'>Visa</div>";
      }
      $item .= "<div class='col-md-12'>{$head}</div>
                  <div class='col-md-12'>{$txt}</div>
                 </div></div>";

      return $item;
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
    $oauth_header .= 'oauth_version="1.0"';
    return ["Authorization: Oauth ={$oauth_header}", 'Expect:'];
    }

    protected function getOAuthSignature($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET){
      $oauth_hash = '';
    $oauth_hash .= 'oauth_consumer_key='.$KEY.'&';
    $oauth_hash .= 'oauth_nonce=' . (string)mt_rand() . '&';
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

    protected function doTwitterOAuth($URL, $KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET,$query)
    {

        $oauth = array(
          'oauth_consumer_key' => $KEY,
          'oauth_token' => $TOKEN,
          'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
          'oauth_timestamp' => time(),
          'oauth_signature_method' => 'HMAC-SHA1',
          'oauth_version' => '1.0'
      );
        $oauth = array_map("rawurlencode", $oauth);
        $query = array_map("rawurlencode", $query);

        $arr = array_merge($oauth, $query); // combine the values THEN sort
        asort($arr); // secondary sort (value)
        ksort($arr); // primary sort (key)
        // http_build_query automatically encodes, but our parameters
        // are already encoded, and must be by this point, so we undo
        // the encoding step
        $querystring = urldecode(http_build_query($arr, '', '&'));
        $url = "https://api.twitter.com/1.1/search/tweets.json";
        // mash everything together for the text to hash
        $base_string = 'GET'."&".rawurlencode($url)."&".rawurlencode($querystring);
        // same with the key
        $key = rawurlencode($KEY_SECRET)."&".rawurlencode($TOKEN_SECRET);

        // generate the hash
        $signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));
        // this time we're using a normal GET query, and we're only encoding the query params
        // (without the oauth params)
        $url .= "?".http_build_query($query);
        $url=str_replace("&amp;","&",$url); //Patch by @Frewuill

        $oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
        ksort($oauth); // probably not necessary, but twitter's demo does it

        // also not necessary, but twitter's demo does this too
        function add_quotes($str) { return '"'.$str.'"'; }
        $oauth = array_map("add_quotes", $oauth);

        // this is the full value of the Authorization line
        $auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

        // if you're doing post, you need to skip the GET building above
        // and instead supply query parameters to CURLOPT_POSTFIELDS
        $options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
                          //CURLOPT_POSTFIELDS => $postfields,
                          CURLOPT_HEADER => false,
                          CURLOPT_URL => $url,
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_SSL_VERIFYPEER => false);

        // do our business
        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);

        return json_decode($json);
      }

  }

  class TW_FEED extends FEED {
    protected $searches     = ['%23LifeAtVisa'];
    protected $API_KEY      = "FCY1AUgBdPEIK7hB2hRaYjtL1";
    protected $API_SECRET   = "68DEk5ud4OF3TjIHH8CZVXF3Tgpe4pv65jPeRZfinne5MPAY7Y";
    protected $ID         = "3438087887";
    protected $TOKEN      = "AAAAAAAAAAAAAAAAAAAAADTOhAAAAAAAteBsp0kGPspWaLpUpLy6iMBP9Q4%3Do6bTQ1kbVgLRh8ylFXDp4LnvdvDzh1cUu4Bu1um1q3UfD7huWw";//3438087887-JSCRMIVejFBWJBaaCSkFEriajRLBsnA8IzdSZNm";
    protected $TOKEN_SECRET = "orc8HKe53kVODPEqMC9nx7YrLhC4sNL3nXkTNIshDUZyW";
    protected $BASE_URL   = "https://api.twitter.com/1.1/search/tweets.json";
    protected $header     = false;
    protected $query      = ['q'=>'%23LifeAtVisa','%23VisaNews','%40visa','result_type'=>'mixed','count'=>'20'];
    protected $SDK        = false;
    protected $tweets     = false;
    public function __construct(){
        \Codebird\Codebird::setConsumerKey($this->API_KEY, $this->API_SECRET);
        \Codebird\Codebird::setBearerToken($this->TOKEN);
        $this->SDK = \Codebird\Codebird::getInstance();
        $this->tweets = $this->SDK->search_tweets('q=' . implode('+OR+',$this->searches) . '&result_type=mixed&count=20', true);
    }

   
    public function formatResults(){
      //d($this->buildURI());
      $tweets = '';
      foreach($this->tweets->statuses as $tweet){
        $item = '<div class="row">';
        $profilelink = "<a href='https://twitter.com/{$tweet->user->screen_name}'>@{$tweet->user->screen_name}</a>";
        $heading = $this->getHeading($tweet->user->name,$profilelink);
        $item .= '<div class="col-md-2 col-xs-2">' ."<img src='{$tweet->user->profile_image_url}' /></div>";
        $item .= "<div class='col-md-10 col-xs-10'>{$heading}<div class='col-md-12'>{$tweet->text}</div></div>";
        $tweets.=$item . '</div>';
      }

      return $tweets;
    }
    public function getHeading($name, $handle){
      return "<div class='col-md-12 head'>{$name} {$handle}</div>";
    }
    public function retrieve(){
      return parent::request();
    }

    public function getHeader(){
      return parent::getOAuthHeader($this->BASE_URL, $this->API_KEY, $this->TOKEN, $this->API_SECRET, $this->TOKEN_SECRET);
    }

    public function buildURI(){
      return $this->BASE_URL . '?q=' . implode('+OR+',$this->searches) . '&result_type=mixed&count=20';
    }

    protected function setOpts($ch){

      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
    curl_setopt($ch, CURLOPT_HEADER, false);
    //d($this->buildURI(),false);
    curl_setopt($ch, CURLOPT_URL, $this->buildURI());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    
  }

  class FB_FEED extends FEED {
    protected $search       = '%23LifeAtVisa';
    protected $API_KEY      = "jOVmbimft5wX6lMU7KsH5S5JS";
    protected $API_SECRET   = "cce55079576f3cbe96828c2d8ffb3ae6";
    protected $ID       = "1614550268832726";
    protected $TOKEN      = '1614550268832726|T_POkQw_mGEsIv5GG4SSAkjsBZM';
    protected $BASE_URI   = "https://graph.facebook.com/134335680053868/feed";

    public function formatResults(){
      $res = json_decode(file_get_contents($this->buildURI()));//d($res);
      //$res = $this->retrieve(); 
      $posts = '';
      $img = get_images_path(false) . "visa_profile_pic.jpg";
      foreach($res->data as $post){
        $date = date('F j, Y',strtotime($post->created_time));
        $msg  = (isset($post->message)) ? $post->message : '';
        $posts.= $this->buildPostList($date,$msg,$img, true);
      }
      return $posts;
    }

    public function retrieve(){
      return parent::request(true,$this->buildURI());
    }
    
    public static function getHeader(){
      return parent::getOAuthHeader($this->BASE_URI, $this->API_KEY, $TOKEN, $KEY_SECRET, $TOKEN_SECRET);
    }
  
    protected static function getToken(){
      $url = "https://graph.facebook.com/oauth/access_token?client_id={$ID}&client_secret={$API_SECRET}&grant_type=client_credentials";
      $resp = $this->request(true,$url);
      $this->TOKEN = str_replace('access_token=','',$resp);
      return $this->TOKEN;
    }

    public function buildURI(){
      return $this->BASE_URI . '?hashtag='. $this->search .'&access_token=' . $this->TOKEN;
    }

    protected function setOpts($ch){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_URL, $this->buildURI());
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    
  }

  class INSTA_FEED
{
    const MEDIA_URI     = "https://api.instagram.com/v1/users/";
    const CLIENT_ID     = "789ce21ba77643c09d15c23f78e0a5ea";//"095d585640c14c02b363a3f8cd8a106f";//"9b01e33ce8df49fca72c5847032672ec";
    const CLIENT_SECRET = "8e7dd1998ffd453c9c66df00778741f6";//1f3885a1a0b8495aa628806ab6194e2a";//"73c1b89c2b074c14b265817994b2e3ec";
    const TOKEN         = "2161282571.1677ed0.5f54824bba544224a0ff9c60f6ef7103";//"1334452974.85b53d1.80b02e49cb4d43328712ac8072ccb9c9";//"1509614661.9b01e33.a7995af906de449496b8634891c8f22a";
    const USER_ID       = "2161282571";//"1509614661";
  
  static function fetch()
  {
    /*$img = get_images_path(false) . 'insta_gall.png';
    return "<img src='{$img}' />";*/
    $data = self::getImages();
    $imgs = '<div class="feed insta_feed row"><div class="col-lg-12">';
    $i=0;
    if($data){
      if(count($data->data) < 3) $data->data[3] = $data->data[0];
    foreach($data->data as $post){
       $imgs .= self::wrapImage($post->images->thumbnail->url,$post->images->standard_resolution->url);
      ++$i;
      if($i == 3){
        $i=0;
        $imgs .= self::getCloseRow();
      }
    }
    return $imgs . '</div></div>';
    }
    
  }  

  static function getCloseRow(){
      return '</div></div><div class="feed insta_feed row">';
  }

  static function wrapImage($img,$imglg){
    return "<div class='col-sm-12 col-xs-12 col-md-12 col-lg-4'><img class='img-responsive visible-lg' src='{$img}' /><img class='img-responsive hidden-lg' src='{$imglg}' /></div>";
  }
 static function getImages(){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::buildURI());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch); 
    return json_decode($result);
  }

  static function buildURI()
  {
    return self::MEDIA_URI.self::USER_ID."/media/recent/?access_token=".self::TOKEN."&count=8";
  }
}

class LI_FEED {
  static function fetch($id = null){
    $id = ($id === null) ? get_the_ID() : $id;
    $feed = ACF_HELPER::getRepeater('linkedin_posts',['img','body','link','name', 'profilelink','date'],$id);
    $html = '';
    foreach($feed as $post){
      //d($post['img']['url']);
      //d($post['body']);
      $img = $post['img']['url'];
      $link = "<a href='{$post['profilelink']}'>{$post['name']}</a>";
      $head =  "<div class='col-md-12 head'>{$post['name']} @{$link}</div>";

      $body = self::findWrapLink($post['body'],$post['link']);//$post['body'] . "<a href='{$post['link']}'> {$post['link']}</a>";

      $html.= self::buildPostList($head,$body,$img);
    }
    //d($html);
    return $html;
  }
  static function findWrapLink($body, $link){
    $lnk = "<a href='{$link}'> {$link}</a>";
    return str_replace($link, $lnk, $body);
  }
  static function buildPostList($head,$txt,$img){
      $item  = "<div class='row'><div class='col-xs-2 col-md-2'><img src='{$img}'></div>";
      $item .= "<div class='col-md-10 col-xs-10'>";
      $item .= "<div class='col-md-12'>{$head}</div>
                  <div class='col-md-12'>{$txt}</div>
                 </div></div>";

      return $item;
    }

  /*private $CLIENT_ID     = "778n94xqyfofdt";
  private $CLIENT_SECRET = "irbuCxPnalry4Md9";
  private $CODE          = "AQRoL_SgiwGwE8uqFlvmIjjvVDTL7-JjuT6MW8TRb-GOygOEZgrhaaqx6RcjI12o4T5klW9hRzrOaOnoS7NSuM2rRIb9G7WihSFioY-_i6mfgsdocVI";
  private $STATE         = "9876ETFF321";
  private $TOKEN         = "AQWfawyezez2XpauQv1gX2aPBDm6dKQViC5liPi0zwTleqLpegSfQ_EVSChyez86EBRgtDUZv4MMtX1VeNqli_H8-urNrSCwuHi8dyGSufhi4Hp59nQHBo27iRtOUMaUtNMIB4eysx2VNUR2u3eXxM0Q440vfedjVvkpaVCpj_y77Sl53S4";
  private $TOKEN_URI     = "https://www.linkedin.com/uas/oauth2/accessToken";
  private $REDIRECT_URI  = "http%3A%2F%2Fvisa.interns.ingenuitydesign.com";
  private $postfields    = [
        'grant_type'   => 'authorization_code',
        'code'         => '', 
        'redirect_uri' => '',
        'client_id'    => '',
        'client_secret'=> ''
  ];

  function __construct(){
        $this->postfields['code']         = $this->CODE;
        $this->postfields['redirect_uri'] = $this->REDIRECT_URI;
        $this->postfields['client_id']    = $this->CLIENT_ID;
        $this->postfields['client_secret']= $this->CLIENT_SECRET;
  }

  protected function getTokenURI()
  {
    return $this->TOKEN_URI . $this->CODE . "&redirect_uri={$this->REDIRECT_URI}&client_id=" 
              . $this->CLIENT_ID . "&client_secret=" . $this->CLIENT_SECRET;
  }*/


}

/*https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=778n94xqyfofdt&redirect_uri=http%3A%2F%2Fvisa.interns.ingenuitydesign.com&state=9876ETFF321&scope=r_basicprofile
https://www.linkedin.com/uas/oauth2/accessTokengrant_type=authorization_code&code=AQSJsdUJrPQLTo3XlNG97-Yim9xTcu7tFIHUiUwbMwAgbpxblBtJOgBEPw-FotiSpFFfSfNA1CA6r627rpeAk17fFWS3ERI09qY5mOpzfepRTR52l4A
&redirect_uri=http%3A%2F%2Fvisa.interns.ingenuitydesign.com&client_id=778n94xqyfofdt&client_secret=irbuCxPnalry4Md9*/