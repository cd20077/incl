<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Filetops Controller
 */
class FiletopsController extends AppController {

/**
 *  Uses
 */
    var $uses = array('User','GroupList','GroupMember','News');
/**
 *  Layout
 */
    public $layout = 'front';
/**
 * Components
 */
    public $components = array('Paginator','Auth' => array('loginAction' => array('controller' => 'tops','action' => 'index')));

    var $authData;

/**
 * beforeFilter method
 */
    public function beforeFilter() {
        parent::beforeFilter();

        if(SessionComponent::check('Admin_login_check')){
            $this->Auth->allow();
	}
        if(SessionComponent::check('login_check')){
            $this->Auth->allow();
            $this->authData = $this->viewVars['auth'];
        }
    }
/**
 * index method
 */
	public function index() {
            $dir = new Folder(WWW_ROOT.USER_DATA_URL.$this->authData['randid'].'/');
            $dirArr = $dir->read();
            mb_convert_variables("UTF-8","SJIS", $dirArr);
            $this->set('dir', $dirArr);
	}
	public function openfol() {
            if($this->request->is('ajax')) {
                if(empty($this->data['id'])){
                    $this->autoRender = FALSE;
                    return "err";
                }else{
                    $this->layout="";
                    if(strpos($this->data['id'], '..') !== false){
                        $this->autoRender = FALSE;
                        return "direrr";
                    }else{
                        if(!file_exists(WWW_ROOT.USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['id'], "SJIS", "AUTO"))){
                            $this->autoRender = FALSE;
                            return "err";
                        }else{
                            if($dir = new Folder(WWW_ROOT.USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['id'], "SJIS", "AUTO").'/')){
                                $dirArr = $dir->read();
                                mb_convert_variables("UTF-8","SJIS", $dirArr);
                                $this->set('dir', $dirArr);
                                $this->set('folname', $this->data['id'].'/');
                            }else{
                                $this->autoRender = FALSE;
                                return "err";

                            }
                        }
                    }

                }
            }else{
                $this->redirect(array('controller' => 'tops', 'action' => "index"));
            }
	}
	public function group($id = null) {
            if($id == null){
                $this->redirect(array('controller' => 'users', 'action' => "grouptop"));
            }else{
                $this->layout="frontgroup";
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

                    $dir = new Folder(WWW_ROOT.PROJECT_DATA_URL.$id.'/');

                    $dirArr = $dir->read();
                    mb_convert_variables("UTF-8","SJIS", $dirArr);
                    $this->set('dir', $dirArr);
                }else{
                    $this->Session->setFlash('<span class="err_mes">'.__('該当のプロジェクトは存在しません。').'</span>');
                    $this->redirect(array('controller' => 'users', 'action' => "grouptop"));
                }
            }
	}
	public function openfol2() {
            if($this->request->is('ajax')) {
                if(empty($this->data['gid'])){
                        $this->autoRender = FALSE;
                        return "err";
                }else{
                    $this->layout="";
                    
                    if((strpos($this->data['gid'], '..') !== false)||(strpos($this->data['dname'], '..') !== false)){
                        $this->autoRender = FALSE;
                        return "direrr";
                    }else{
                        $this->User->recursive = 0;
                        $this->GroupMember->recursive = 3;
                        $groupMembers = $this->GroupMember->find('first',array(
                            'conditions' => array(
                                'GroupMember.user_id' => $this->authData['id'],
                                'GroupList.ranid' => $this->data['gid']
                                )
                            )
                        );
                        if($groupMembers){
                            if(!file_exists(WWW_ROOT.PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO"))){
                                $this->autoRender = FALSE;
                                return "err";
                            }else{
                                $this->set(compact('groupMembers'));

                                $dir = new Folder(WWW_ROOT.PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO"));

                                $dirArr = $dir->read();
                                mb_convert_variables("UTF-8","SJIS", $dirArr);
                                $this->set('dir', $dirArr);
                                $this->set('folname', $this->data['dname'].'/');
                            }
                        }else{
                            $this->autoRender = FALSE;
                            return "err";
                        }
                    }

                }
            }else{
                    $this->redirect(array('controller' => 'tops', 'action' => "index"));
            }
	}
}