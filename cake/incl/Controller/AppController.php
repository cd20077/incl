<?php

//App::import('Vendor', 'tcpdf/tcpdf');
//App::import('Vendor', 'tcpdf/examples/lang/eng');
/**
 * Application level Controller
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 */
 config('settings');

class AppController extends Controller {

	public $components = array('DebugKit.Toolbar','Session');
        
	public function beforeFilter(){
		parent::beforeFilter();
                // DebugKitの停止
                Configure::write('debug', 0);
		$prefix = isset($this->params['prefix']) ?  $this->params['prefix'] : '';
		$this->auto_login_key = bin2hex(WWW_ROOT.'auto_login'.$prefix);

                $this->auto_login_key = bin2hex(WWW_ROOT.'auto_login'.$prefix);
                $User              = ClassRegistry::init('User');

                // ログイン状態のチェック
                if($this->Session->check($this->auto_login_key)){
                        
                        //$uses = array('User');
			//$this->User->recursive = -1;
                        
                        $auth_id = $this->Session->read($this->auto_login_key);
                        $auth    = $User->find('first', array(
                            'conditions' => array(
                                'User.id' => intval($auth_id),
                                'User.status_id' => 2
                            ),
                            'callbacks' => 'beforeFind'
                        ));
                        if($auth){
                            /// ユーザー認証に成功した場合
                            /*
                            */
                            //日本語
                            //if($auth['User']['langid'] == 1)
                            //英語
                            if($auth['User']['langid'] == 2){
                                Configure::write( 'Config.language', 'en');
                            }
                            
                            $this->auth = $auth['User'];
                            $this->set('auth', $auth['User']);
                            $this->Session->write('login_check', 'ok');


                        }else{
                            //ユーザー認証に失敗した場合
                            $this->redirect(array('controller'=>'top', 'action'=>"index"));
                            //$this->Session->setFlash('ログインしてないっす');
                        }

                }
	}
}

