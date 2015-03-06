<?php
class AuthsController extends AppController {

	/**
 *  Layout
 */
	public $layout = 'front';
 /* @var string
 */
	//public $components = array('Session');

	var $name = 'Auths';
	var $helpers = array('Html');
	var $uses = array('User');


	// ● 管理者ログイン
	public function admin_login(){

		//$this->Session->destroy();
		$prefix = $this->params['prefix'];

		 // ログインボタンを押下した場合
		if(!empty($this->data)){

			$auth = $this->Admin->find('first', array(
				'conditions'=>array(
					'OR' => array(
						array(
							'name'      => $this->data['Auths']['login_cd'],
							'password'  => crypt($this->data['Auths']['login_pw'],SOLT_LOGIN_PASSWORD)
						),
						array(
							'name'      => $this->data['Auths']['login_cd'],
							'password'  => $this->data['Auths']['login_pw']
						)
					)
				),
				'callbacks' => 'beforeFind'
			));

			// ログイン成功
			if($auth){

				$this->Session->write($this->auto_login_key, $auth['Admin']['id']);
				$this->auth = $auth['Admin'];
				$this->set('auth', $auth['Admin']);
				$this->redirect(array('controller' => 'Users', 'action' => "{$prefix}_index"));

			// ログイン失敗
			}else{
				$this->Session->setFlash('ログインに失敗しました。');
			}
		 // ログイン画面にアクセスした場合
		}else{

		}
	}

	// ● 管理者ログアウト
	function admin_logout(){
		$prefix = $this->params['prefix'];

		$this->Session->destroy();
		$this->Session->setFlash('ログアウトしました');
		$this->redirect(array('controller' => 'auths', 'action' => "{$prefix}_login"));
	}


/**
 * フロントログイン画面
 */
	public function login() {

		$this->layout="ajax";

		 // ログインボタンを押下した場合
		if(!empty($this->data)){

			$auth = $this->User->find('first', array(
				'conditions'=>array(
					array(
						'OR' => array(
							array(
								'mail'      => $this->data['Auth']['login_mail'],
								'password'  => crypt($this->data['Auth']['login_pw'],SOLT_LOGIN_PASSWORD)
							),
							array(
								'mail'      => $this->data['Auth']['login_mail'],
								'password'  => $this->data['Auth']['login_pw']
							)
						)
					),
					array(
						'status_id' => '2'
					)
				),
				'callbacks' => 'beforeFind'
			));

			// ログイン成功
			if($auth){
				$this->Session->write($this->auto_login_key, $auth['User']['id']);
				$this->Auth = $auth['User'];
				$this->set('auth', $auth['User']);
				//$this->redirect(array('controller' => 'filetops', 'action' => "index"));
				$this->redirect(array('controller' => 'auths', 'action' => "loginlink"));

			// ログイン失敗
			}else{
				$this->Session->setFlash('<p class="err_mes">メールアドレスまたは、パスワードが違います。</p>');
			}
		 // ログイン画面にアクセスした場合
		}else{

		}

	}
	public function loginlink() {

		$this->layout="ajax";
		

	}

	// ● フロントログアウト
	public function logout(){

		$this->Session->destroy();
		//$this->Session->setFlash('ログアウトしました');
		//$this->redirect(array('controller' => 'auths', 'action' => "logout"));
		$this->redirect(array('controller' => 'tops', 'action' => "index"));
	}
	
	public function facebook($cb = 0){
		$this->layout = false;
		if($cb == 1){
			require_once(ROOT.DS.APP_DIR.DS.'Vendor'.DS.'OAuth'.DS.'facebook'.DS.'facebook.php');
			
			$facebookAppArray = array(
				'appId'  => FACEBOOK_CLIENT_ID,
				'secret' => FACEBOOK_SECRET
			);

			$facebook = new Facebook($facebookAppArray);
			//戻り値0の場合未ログイン
			$facebookUserId  = $facebook -> getUser();
			if ($facebookUserId == 0) {
				$facebookLoginUrl = $facebook -> getLoginUrl();
				$this->redirect($facebookLoginUrl);
			}
			var_dump($facebookLoginUrl);
			//$this->redirect("https://graph.facebook.com/oauth/access_token?client_id=".FACEBOOK_CLIENT_ID."&redirect_uri=".FACEBOOK_REDIRECT_URI."/2/&client_secret=".FACEBOOK_SECRET."&code={$_REQUEST['code']}");
			var_dump($facebookUserId);
			exit;
		}
		if($cb == 2){
			var_dump($_REQUEST);
			exit;
		}
		$this->redirect("https://www.facebook.com/dialog/oauth/?scope=status_update,publish_stream&client_id=".FACEBOOK_CLIENT_ID."&redirect_uri=".FACEBOOK_REDIRECT_URI.'1/');
		
		
	}

}
