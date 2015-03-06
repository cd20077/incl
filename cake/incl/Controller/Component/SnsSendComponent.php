<?php
App::uses('Component', 'Controller');

class SnsSendComponent extends Component {

	private $auth;

	private $twitter_oauth_token        = null;
	private $twitter_oauth_token_secret = null;
	private $twitter_user_code          = null;
	private $facebook_client_token      = null;

	public function initialize(Controller $controller) {
		
	}
	

	public function __construct(ComponentCollection $collection, $settings = array()) {
//		$this->controller = $controller;
		parent::__construct($collection, $settings);
	}

	public function setUserId($user_id){
		App::uses('User', 'Model');
		$UMDL = new User;
		
		$user = $UMDL->findById($user_id);
		$this->twitter_oauth_token        = $user['User']['twitter_oauth_token'];
		$this->twitter_oauth_token_secret = $user['User']['twitter_oauth_token_secret'];
		$this->twitter_user_code          = $user['User']['twitter_user_code'];
		$this->facebook_client_token      = $user['User']['facebook_client_token'];
	}

	public function sendAll($msg){
		$this->doTweet($msg);
		$this->sendFacebook($msg);
	}

	public function doTweet($msg) {
		@require_once(ROOT.DS.APP_DIR.DS.'Vendor'.DS.'OAuth'.DS.'twitteroauth.php');
		$url = "https://api.twitter.com/1.1/statuses/update.json";
		$method = "POST";
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,$this->twitter_oauth_token,$this->twitter_oauth_token_secret);
		$connection->oAuthRequest($url, $method, array("status"=>$msg));
	}
	public function sendFacebook($msg) {
		require_once(ROOT.DS.APP_DIR.DS.'Vendor'.DS.'OAuth'.DS.'facebook'.DS.'facebook.php');
		$facebook = new Facebook(array(
			'appId'  => FACEBOOK_CLIENT_ID,
			'secret' => FACEBOOK_SECRET,
		));
		
		$facebook->setAccessToken($this->facebook_client_token);
		//var_dump($access_token::accessToken);
		var_dump($facebook->api('/' . '1431985733688615' . '/feed/', 'POST', array('access_token' => $this->facebook_client_token, 'message' => $msg)));
	}
}
