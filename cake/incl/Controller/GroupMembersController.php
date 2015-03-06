<?php
App::uses('AppController', 'Controller');
/**
 * GroupMembers Controller
 */
class GroupMembersController extends AppController {

	var $uses = array('User','GroupList','GroupMember','News');
/**
 *  Layout
 */
	public $layout = 'front';
/**
 * Components
 */
	public $components = array('Paginator','Auth' => array('loginAction' => array('controller' => 'tops','action' => 'index')));

    public function beforeFilter() {
        parent::beforeFilter();

        // 非ログイン時にも実行可能とする
        //$this->Auth->allow( array( ''));

        if(SessionComponent::check('Admin_login_check')){
        	$this->Auth->allow();
	}
        if(SessionComponent::check('login_check')){
                $this->Auth->allow();
                $this->authData = $this->viewVars['auth'];
        }
    }
/**
 * プロジェクト詳細
 */
	public function groupdetail($id = null) {
		if($id == null){
			$this->redirect(array('controller' => 'users', 'action' => "grouptop"));
		}else{
			$this->layout="usertop";
			$this->User->recursive = 0;
			$this->GroupMember->recursive = 3;
			$groupMembers = $this->GroupMember->find('first',array(
				'conditions' => array(
					'GroupMember.user_id' => $this->authData['id'],
					'GroupList.ranid' => $id
					)
				)
			);
			if($groupMembers){
				$news = $this->News->find('all',array(
					'conditions' => array('News.user_id' => $this->authData['id']),
					'order' => array('News.modified DESC'),
					'limit' => 30
					)
				);
				$this->set(compact('groupMembers','news'));
				//$this->set('users', $this->Paginator->paginate());
			}else{
				$this->Session->setFlash('<span class="err_mes">'.__('該当のプロジェクトは存在しません。').'</span>');
				$this->redirect(array('controller' => 'users', 'action' => "grouptop"));
			}
		}
	}
    /**
     * Ajaxメンバー編集時用
     */
    public function addmember() {
            if($this->request->is('ajax')) {
                    if(empty($this->data['id'])){
                            $this->autoRender = FALSE;
                            return "err";
                    }else{
                            $this->layout="";
                            $this->GroupMember->recursive = 3;
                            $groupMembers = $this->GroupMember->find('first',array(
                                    'conditions' => array(
                                            'GroupMember.user_id' => $this->authData['id'],
                                            'GroupList.ranid' => $this->data['id']
                                            )
                                    )
                            );
                            if($groupMembers){
                                    $this->set(compact('groupMembers'));
                            }else{
                                    $this->autoRender = FALSE;
                                    return "err";
                            }
                    }
            }else{
                    $this->autoRender = FALSE;
                    return "err";
            }
    }

    /**
     * チャット用
     */
    public function cometfunc() {
        $this->autoRender = FALSE;
        $this->layout = '';
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {

            if(!$this->data) exit;

            $max   = PROJECT_CHAT_MAX; //記録件数
            $file  = WWW_ROOT.PROJECT_CHAT_URL.$this->data['gid'].'.log';  //ログ記録ファイル
            $admin = '管理人'; //管理人の名前
            $xdata = '';
            $xdata .= '<?xml version="1.0" encoding="UTF-8" ?> ' . "\n";
            $loglasttime = '';

            /*
             * 全ログを取り出す
             * 
             */
            if($this->data['type'] == 'log') {
               $bbsLog = file($file);
               $bbsLog = array_reverse($bbsLog);
               
               $xdata .= '<rss>';
               
                foreach( $bbsLog AS $row):
                    $log = explode("<>",$row);

                    $xdata .= '<item xml:space="preserve"><name>'.$log[1].'</name><log>'.$log[2].'</log><date>'.$log[3].'</date></item>';
                    $loglasttime = $log[3];
                endforeach;
                
                $xdata .= '</rss>';
            }

            /*
             * 入室メッセージを記録する
             */
            elseif($this->data['type'] == 'roomin') {
               $str = strip_tags($this->data['str']); //HTMLタグを除去
               $str = htmlspecialchars($str , ENT_QUOTES , "UTF-8"); // 特殊文字を HTML エンティティに変換する
               $this->GroupMember->writeLog($str,$admin,$file,$max);
            }
            /*
             * 送信されたチャットをファイルに記録する
             */
            else{
                $str = strip_tags($this->data['str']); //HTMLタグを除去
                $str = htmlspecialchars($str , ENT_QUOTES , "UTF-8"); // 特殊文字を HTML エンティティに変換する
                $this->GroupMember->writeLog($str,$this->authData['name'],$file,$max);

                $xdata .= '<rss><item xml:space="preserve"><log>'.$str.'</log><name>'.$this->authData['name'].'</name></item></rss>';
                
                echo $xdata;

                exit;
            }
            /*
            */
            if(strtotime($this->data['llt']) >= strtotime($loglasttime)){
                echo 'none';
            }else{
                header("Content-type: application/xml");
                echo $xdata;
            }
            
            //echo $xdata;
            
        }else{
                $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
}
