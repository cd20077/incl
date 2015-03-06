<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 */
class UsersController extends AppController {

    var $uses = array('User', 'GroupList', 'GroupMember', 'News');

    /**
     *  Layout
     */
    public $layout = 'front';

    /**
     * Components
     */
    public $components = array('Paginator', 'Auth' => array('loginAction' => array('controller' => 'tops', 'action' => 'index')));

    public function beforeFilter() {
        parent::beforeFilter();

        // 非ログイン時にも実行可能とする
        $this->Auth->allow(array('entry_input', 'entry_conf', 'entry_comp', 'entry_already', 'pass_reset', 'pass_reset_conf', 'pass_reset_edit', 'profile_detail', 'secede_comp'));

        if (SessionComponent::check('Admin_login_check')) {
            $this->Auth->allow();
        }
        if (SessionComponent::check('login_check')) {
            $this->Auth->allow();
            $this->authData = $this->viewVars['auth'];
        }
    }

    /**
     * index method
     */
    public function usertop() {
        $this->layout = "usertop";
        $this->User->recursive = 0;
        $this->GroupMember->recursive = 2;
        $groupMembers = $this->GroupMember->find('all', array(
            'conditions' => array('GroupMember.user_id' => $this->authData['id'])
                )
        );
        $this->News->recursive = 2;
        $news = $this->News->find('all', array(
            'conditions' => array('News.user_id' => $this->authData['id']),
            'order' => array('News.modified DESC'),
            'limit' => 30
                )
        );
        $this->set(compact('groupMembers', 'news'));
        //$this->set('users', $this->Paginator->paginate());
    }

    /**
     * index method
     */
    public function grouptop() {
        $this->layout = "usertop";
        $this->User->recursive = 0;
        $this->GroupMember->recursive = 2;
        $groupMembers = $this->GroupMember->find('all', array(
            'conditions' => array('GroupMember.user_id' => $this->authData['id']),
            'order' => array('GroupList.modified DESC'),
                )
        );
        $admincnt = $this->GroupList->find('first', array(
            'fields' => array('count(GroupList.user_id) as admincnt'), // Model__エイリアスにする
            'conditions' => array('GroupList.user_id' => $this->authData['id']),
            'group' => array('GroupList.user_id'),
        ));
        $this->set(compact('groupMembers', 'admincnt'));
    }

    /**
     * index method
     */
    public function groupadd() {
        if ($this->request->is('ajax')) {
            $this->layout = "ajax";
            $this->GroupMember->recursive = 2;
            $groupMembers = $this->GroupMember->find('all', array(
                'conditions' => array('GroupMember.user_id' => $this->authData['id']),
                'order' => array('GroupList.modified DESC'),
                    )
            );
            $this->set(compact('groupMembers'));
        } else {
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }

    /**
     * index method
     */
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
    }

    /**
     * 新規会員登録入力画面
     */
    public function entry_input() {

        $this->layout = "ajax";
        // 投稿された場合
        if (!empty($this->data)) {
            date_default_timezone_set('Asia/Tokyo');

            $ranid = "";
            do {
                $ranid = sha1(uniqid(mt_rand(), true));
            } while (1 == $this->User->find('all', Array('conditions' => Array('randnum' => $ranid))));
            $this->request->data['User']['randnum'] = $ranid;
            $this->request->data['User']['maxcapa'] = DEF_MAX_CAPA;
            $this->request->data['User']['precapa'] = DEF_PRE_CAPA;


            // postデータとモデルをバインド
            $this->User->set($this->data);

            if ($this->User->validates()) {
                // バリデーションOKの場合の処理
                $this->User->create($data = array());

                $this->request->data['User']['password'] = crypt($this->request->data['User']['password'], SOLT_LOGIN_PASSWORD);

                if ($this->User->save($this->data)) {

                    App::import('Model', 'SendMail');
                    $SendMail = new SendMail;

                    App::import('Model', 'Mail');
                    $Mail = new Mail;

                    $result = $Mail->find(
                        'all', Array(
                            'conditions' => Array('id' => '1'),
                            'fields' => Array('Mail.title', 'Mail.text')
                        )
                    );

                    $mail_title = $result[0]['Mail']['title'];
                    $mail_text = $result[0]['Mail']['text'];

                    $mail_url = "http://localhost:1025/cake/incl/tops/index/" . $this->data['User']['randnum'];

                    $uad = $this->request->data['User']['mail'];
                    $mail_text_un = str_replace("%uad%", $uad, $mail_text);
                    $mail_text_con = str_replace("%mail_url%", $mail_url, $mail_text_un);

                    $data = array('to_address' => $uad, 'subject' => mb_convert_encoding($mail_title, "SJIS", "AUTO"), 'body' => mb_convert_encoding($mail_text_con, "SJIS", "AUTO"), 'from_address' => SPI_SM_POST, 'server_ip' => SPI_SM_HOST, 'server_name' => SPI_SM_HOST);


                    if ($SendMail->save($data)) {
                        $this->redirect(array('action' => 'entry_conf'));
                        // 保存に成功した時の処理
                    } else {
                        
                    }
                } else {
                    //$this->Session->setFlash(__('The area could not be saved. Please, try again.'));
                }
            } else {
                // バリデーションNGの場合の処理
                //$this->Session->setFlash(__('仮登録に失敗しました. 再度入力してください。'));
            }
        } else {
            
        }
    }

    /**
     * 新規会員登録確認画面
     */
    public function entry_conf($param1 = "") {

        $this->layout = "ajax";
        
        if (!empty($param1)) {
            date_default_timezone_set('Asia/Tokyo');
            $ranid = $this->params['pass'][0];
            $enuser = $this->User->find('first', Array('conditions' => Array('randnum' => $ranid, 'status_id' => 1), 'fields' => Array('User.id', 'User.mail', 'User.randnum')));
            //print_r($enuser);

            if (0 != count($enuser)) {
                $enuser2 = $this->User->find('first', Array('conditions' => Array('mail' => $enuser['User']['mail'], 'status_id' => 2), 'fields' => Array('User.id')));
                if (0 != count($enuser2)) {
                    return $this->redirect(array('controller' => 'users', 'action' => 'entry_already'));
                } else {
                    $randid = crypt($enuser['User']['id'], SOLT_LOGIN_PASSWORD);
                    $userimg = 'no_image.png';
                    $backimg = 'back.png';
                    $data['User']['id'] = $enuser['User']['id'];
                    $data['User']['name'] = 'ゲスト';
                    $data['User']['status_id'] = '2';
                    $data['User']['userimg'] = USER_IMG_URL . $randid . '/' . $userimg;
                    $data['User']['backimg'] = BACK_IMG_URL . $randid . '/' . $backimg;
                    $data['User']['randid'] = $randid;
                    $dir = new Folder();
                    if ($dir->create(USER_DATA_URL . $randid . '/')) {
                        if ($dir->create('img/userimg/' . $randid . '/')) {
                            $file = new File(DEF_USERIMG_URL);
                            $file->copy('img/userimg/' . $randid . '/' . $userimg, true);
                        }
                        if ($dir->create(BACK_IMG_URL . $randid . '/')) {
                            $file2 = new File(DEF_BACKIMG_URL);
                            $file2->copy(BACK_IMG_URL . $randid . '/' . $backimg, true);
                        }
                    }

                    // 登録するフィールド
                    $fields = array('name', 'status_id', 'userimg', 'backimg', 'randid');

                    //print_r($data);

                    if ($this->User->save($data, false, $fields)) {

                        $newsdata['News']['title'] = '会員登録が完了しました。';
                        $newsdata['News']['user_id'] = $enuser['User']['id'];

                        // 登録するフィールド
                        $fields2 = array('title', 'user_id');

                        if ($this->News->save($newsdata, false, $fields2)) {
                            return $this->redirect(array('action' => 'entry_comp'));
                        }
                    } else {
                        //$this->Session->setFlash(__('本登録に失敗しました. 再度入力してください。'));
                    }
                }
                //仮登録いない→登録済み
            } else {
                return $this->redirect(array('controller' => 'users', 'action' => 'entry_already'));
            }

            //直リンク
        } else {
            App::uses('AppShell', 'Console/Command');
            App::uses('Mail2Shell', 'Console/Command');
            $shell = new Mail2Shell();
            $shell->startup();
            $shell->send();
        }
    }

    /**
     * 新規会員登録完了画面
     */
    public function entry_comp() {

        $this->layout = "ajax";
    }

    /**
     * 会員登録済み画面
     */
    public function entry_already() {

        $this->layout = "ajax";
    }

    /**
     * 会員登録済み画面
     */
    public function pass_reset() {

        $this->layout = "ajax";
    }
    public function gchan($param1 = "") {
        //$this->autoRender = FALSE;
	//date_default_timezone_set('Asia/Tokyo');
        //$this->layout = "";
        //Configure::write( 'Config.language', 'en');
        $this->redirect(array('controller'=>'users','action' => 'usertop'));
    }

}
