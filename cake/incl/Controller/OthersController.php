<?php
App::uses('AppController', 'Controller');
/**
 * Others Controller
 */
class OthersController extends AppController {

    var $uses = array('User');
/**
 *  Layout
 */
    public $layout = 'usertop';
/**
 * Components
 */
    public $components = array('Paginator','Auth' => array('loginAction' => array('controller' => 'tops','action' => 'index')));

    public function beforeFilter() {
        parent::beforeFilter();
        
        if(SessionComponent::check('login_check')){
            $this->Auth->allow();
            $this->authData = $this->viewVars['auth'];
        }
    }
/**
 * ヘルプ
 */
    public function help() {
        $this->layout="usertop";
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
    }
/**
 * 利用規約
 */
    public function agreement() {
        $this->layout="usertop";
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
    }
}
