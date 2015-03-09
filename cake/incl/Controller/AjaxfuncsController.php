<?php
App::uses('AppController', 'Controller');
/**
 * Ajaxfuncs Controller
 */
class AjaxfuncsController extends AppController {

    var $uses = array('User','News','GroupList','GroupMember');
/**
 *  Layout
 */
    public $layout = '';
/**
 * Components
 */
    public $components = array('RequestHandler','Zip');
    var $authData;
/**
 * beforeFilter method
 */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->authData = $this->viewVars['auth'];
    }
/**
 * ajaxupfile//ok
 */
    public function ajaxupfile() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        $dir = new Folder(USER_DATA_URL.$this->authData['randid'].'/');
        if($this->request->is('ajax')) {
            if($files = $dir->find($_FILES['file']['name'], true)){
                $fileNameprt = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $i = 1;
                $filename = $fileNameprt." (".$i.").".$extension;
                while(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .$filename)){
                        $i++;
                        $filename = $fileNameprt." (".$i.").".$extension;
                }
                if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], USER_DATA_URL.$this->authData['randid'].'/' . mb_convert_encoding($filename, "SJIS", "AUTO"));

                    $backdata['User']['id'] = $this->authData['id'];
                    $backdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                    // 登録するフィールド
                    $fields = array('precapa');

                    if ($this->User->save($backdata, false, $fields)) {

                        $newsdata['News']['title'] = $filename.'をアップしました。';
                        $newsdata['News']['user_id'] = $this->authData['id'];

                        // 登録するフィールド
                        $fields2 = array('title','user_id');

                        if ($this->News->save($newsdata, false, $fields2)) {
                            echo $filename;   //echoでもOK
                        }
                    }
                }else{
                    //echo "さん、こんにちは";   //echoでもOK
                }
            }else{
                if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], USER_DATA_URL.$this->authData['randid'].'/' . mb_convert_encoding($_FILES['file']['name'], "SJIS", "AUTO"));
                    $backdata['User']['id'] = $this->authData['id'];
                    $backdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                    // 登録するフィールド
                    $fields = array('precapa');

                    if ($this->User->save($backdata, false, $fields)) {

                        $newsdata['News']['title'] = $_FILES['file']['name'].'をアップしました。';
                        $newsdata['News']['user_id'] = $this->authData['id'];

                        // 登録するフィールド
                        $fields2 = array('title','user_id');

                        if ($this->News->save($newsdata, false, $fields2)) {
                            echo $_FILES['file']['name'];   //echoでもOK
                        }

                    }

                }else{
                    //echo "さん、こんにちは";   //echoでもOK
                }
            }

        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    
/**
 * ajaxnamechan//ok
 */
    public function ajaxnamechan() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {

            $backdata['User']['id'] = $this->authData['id'];
            $backdata['User']['name'] = $this->data['name'];

            // 登録するフィールド
            $fields = array('name');

            if ($this->User->save($backdata, false, $fields)) {
                $newsdata['News']['title'] = 'ユーザー名を変更しました。';
                $newsdata['News']['user_id'] = $this->authData['id'];

                // 登録するフィールド
                $fields2 = array('title','user_id');

                if ($this->News->save($newsdata, false, $fields2)) {
                    return 'ok';
                }

            }else{
                return "err";
            }
            
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    
/**
 * ajaxupuserimg//ok
 */
    public function ajaxupuserimg() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $imgurl = USER_IMG_URL.$this->authData['randid'].'/'.mb_convert_encoding($_FILES['file']['name'], "SJIS", "AUTO");

            if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $imgurl);

                $imgdata['User']['id'] = $this->authData['id'];
                $imgdata['User']['userimg'] = $imgurl;

                // 登録するフィールド
                $fields = array('userimg');

                if ($this->User->save($imgdata, false, $fields)) {
                    $file1 = new File($this->authData['userimg']);
                    $file1->delete();

                    $newsdata['News']['title'] = 'ユーザーイメージを変更しました。';
                    $newsdata['News']['user_id'] = $this->authData['id'];

                    // 登録するフィールド
                    $fields2 = array('title','user_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return $imgurl;
                    }

                }else{
                    return "err";
                }

            }else{
                //echo "さん、こんにちは";   //echoでもOK
            }
            
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxupbackimg//ok
 */
    public function ajaxupbackimg() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $backurl = BACK_IMG_URL.$this->authData['randid'].'/'.mb_convert_encoding($_FILES['file']['name'], "SJIS", "AUTO");

            if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $backurl);

                $imgdata['User']['id'] = $this->authData['id'];
                $imgdata['User']['backimg'] = $backurl;

                // 登録するフィールド
                $fields = array('backimg');

                if ($this->User->save($imgdata, false, $fields)) {
                    $file1 = new File($this->authData['backimg']);
                    $file1->delete();

                    $newsdata['News']['title'] = '背景を変更しました。';
                    $newsdata['News']['user_id'] = $this->authData['id'];

                    // 登録するフィールド
                    $fields2 = array('title','user_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return $backurl;
                    }

                }else{
                    return "err";
                }

            }else{
                //echo "さん、こんにちは";   //echoでもOK
            }
            
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxproadd//ok
 */
    public function ajaxproadd() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $backimg = 'back.png';
            $ranid='';
            do{
                $ranid = sha1( uniqid( mt_rand(), true ));
            }while(1 == $this->GroupList->find('all',Array('conditions' => Array('ranid' => $ranid))));
            $prodata['GroupList']['name'] = $this->data['name'];
            $prodata['GroupList']['ranid'] = $ranid;
            $prodata['GroupList']['user_id'] = $this->authData['id'];
            $prodata['GroupList']['maxcapa'] = DEF_PROJECT_MAX_CAPA;
            $prodata['GroupList']['precapa'] = DEF_PROJECT_PRE_CAPA;
            $prodata['GroupList']['backimg'] = PROJECT_IMG_URL.$ranid.'/'.$backimg;

            $this->GroupList->create();
            if ($this->GroupList->save($prodata, false)) {
                $last_id = $this->GroupList->getLastInsertID();
                $promemdata['GroupMember']['group_list_id'] = $last_id;
                $promemdata['GroupMember']['user_id'] = $this->authData['id'];
                $promemdata['GroupMember']['auth_level_id'] = '1';

                $this->GroupMember->create();
                if ($this->GroupMember->save($promemdata, false)) {
                    $dir = new Folder();
                    if ($dir->create(PROJECT_DATA_URL.$ranid.'/')) {
                        if ($dir->create(PROJECT_IMG_URL.$ranid.'/')) {
                            $file2 = new File(DEF_BACKIMG_URL);
                            $file2->copy(PROJECT_IMG_URL.$ranid.'/'.$backimg,true);
                            $file3 = new File(DEF_PROLOG_URL);
                            $file3->copy(PROJECT_CHAT_URL.$ranid.'.log',true);
                        }
                    }
                    $newsdata['News']['title'] = '新規プロジェクトを作成しました。';
                    $newsdata['News']['user_id'] = $this->authData['id'];

                    // 登録するフィールド
                    $fields2 = array('title','user_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return 'ok';
                    } else {
                        return "err";
                    }
                }else{

                }
            } else {
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxpronamechan//ok
 */
    public function ajaxpronamechan() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $this->GroupList->recursive = -1;
            if($gdata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){
                $gdata['GroupList']['name'] = $this->data['name'];

                // 登録するフィールド
                $fields = array('name');

                if ($this->GroupList->save($gdata, false, $fields)) {
                    $newsdata['News']['title'] = 'プロジェクト名を変更しました。';
                    $newsdata['News']['group_list_id'] = $gdata['GroupList']['id'];

                    // 登録するフィールド
                    $fields2 = array('title','group_list_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return 'ok';
                    }

                }else{
                    return "err";
                    }
            }else{
                return "err";
            }
            
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxsearchumail//ok
 */
    public function ajaxsearchumail() {
        $this->autoRender = FALSE;
        if($this->request->is('ajax')) {
            $this->User->recursive = -1;
            if($gdata = $this->User->find('first',Array('conditions' => Array('mail' => $this->data['mail'],'status_id' => 2),'fields' => Array('id','name','mail','userimg')))){
                $this->GroupList->recursive = -1;
                $giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')));
                $this->GroupMember->recursive = -1;
                if($gdata2 = $this->GroupMember->find('first',Array('conditions' => Array('user_id' => $gdata['User']['id'],'group_list_id' => $giddata['GroupList']['id']),'fields' => Array('id')))){
                    return "err2";
                }else{
                    echo 'メールアドレス:'.$this->data['mail'].'<br />ユーザー名：'.$gdata['User']['name'];
                }
            }else{
                return "err1";
            }
            
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxaddmem//ok
 */
    public function ajaxaddmem() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $this->User->recursive = -1;
            if($gdata = $this->User->find('first',Array('conditions' => Array('mail' => $this->data['mail'],'status_id' => 2),'fields' => Array('id','name')))){

                $this->GroupList->recursive = -1;
                $giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')));

                $promemdata['GroupMember']['group_list_id'] = $giddata['GroupList']['id'];
                $promemdata['GroupMember']['user_id'] = $gdata['User']['id'];
                $promemdata['GroupMember']['auth_level_id'] = '2';

                $this->GroupMember->create();
                if ($this->GroupMember->save($promemdata, false)) {

                    $newsdata['News']['title'] = $gdata['User']['name'].'をプロジェクトメンバーに追加しました。';
                    $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                    // 登録するフィールド
                    $fields2 = array('title','group_list_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return 'ok';
                    } else {
                        return "err";
                    }
                }else{

                }
            }else{
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxmemdel//ok
 */
    public function ajaxmemdel() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $this->GroupList->recursive = -1;
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['gid']),'fields' => Array('id')))){

                $this->GroupMember->id = $this->data['memid'];
                if ($this->GroupMember->delete()) {

                    $newsdata['News']['title'] = $this->data['name'].'が脱退しました。';
                    $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                    // 登録するフィールド
                    $fields2 = array('title','group_list_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return 'ok';
                    } else {
                        return "err";
                    }
                }else{
                    return "err";
                }
            }else{
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxchanauth//ok
 */
    public function ajaxchanauth() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $this->GroupList->recursive = -1;
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['gid']),'fields' => Array('id')))){

                $authlevel = 2;
                switch ($this->data['authid']){
                case '1':
                    $authlevel = 2;
                    break;
                case '2':
                    $authlevel = 1;
                    break;
                default:
                    // 処理
                    $authlevel = 2;
                }

                $promemdata['GroupMember']['id'] = $this->data['memid'];
                $promemdata['GroupMember']['auth_level_id'] = $authlevel;

                // 登録するフィールド
                $fields = array('auth_level_id');
                if ($this->GroupMember->save($promemdata, false, $fields)) {

                    $newsdata['News']['title'] = $this->data['name'].'の権限を変更しました。';
                    $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                    // 登録するフィールド
                    $fields2 = array('title','group_list_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return 'ok';
                    } else {
                        return "err";
                    }
                }else{

                }
            }else{
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxappfol//ok
 */
    public function ajaxappfol() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $folname = $this->data['name'];
            if (strpos($folname, '..') !== false) {
                return "direrr";
            }else{
                if(file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($folname, "SJIS", "AUTO").'/')){
                    $i = 1;
                    $folprt = $folname;
                    $folname = $folprt." (".$i.")";
                    while(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .mb_convert_encoding($folname, "SJIS", "AUTO").'/')){
                        $i++;
                        $folname = $folprt." (".$i.")";
                    }
                }
                $dir = new Folder();
                if($dir->create(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($folname, "SJIS", "AUTO").'/')){

                    $newsdata['News']['title'] = $this->data['name'].'を作成しました。';
                    $newsdata['News']['user_id'] = $this->authData['id'];

                    // 登録するフィールド
                    $fields2 = array('title','user_id');

                    if ($this->News->save($newsdata, false, $fields2)) {
                        return $folname;
                    }
                }else{
                    return "err";
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxfoltrash//ok
 */
    public function ajaxfoltrash() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if (strpos($this->data['name'], '..') !== false) {
                return "direrr";
            }else{
                $folurl = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO").'/';
                $folder = new Folder($folurl);
                if(!file_exists($folurl)){
                    return "not";
                }else{
                    if($folder->delete()){
                        $trashdata['User']['id'] = $this->authData['id'];
                        $trashdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                        // 登録するフィールド
                        $fields = array('precapa');

                        if ($this->User->save($trashdata, false, $fields)) {

                            $newsdata['News']['title'] = $this->data['name'].'を削除しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                return "ok";
                            }
                        }
                    }else{
                        return "err";
                    }
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxdownfol//ok
 */
    public function ajaxdownfol($file_name = null) {
        $this->autoRender = FALSE;
        if($file_name) {
            if (strpos($file_name, '..') !== false) {
                echo '<script>alert("該当のフォルダが存在しません。");parent.$.colorbox.close();</script>';
            }else{
                if(file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding(str_replace('*', '/', $file_name), "SJIS", "AUTO"))){
                    $filename2 = strrchr( "/".str_replace('*', '/', $file_name), "/" );//
                    $folname = substr( $filename2, 1 );//
                    $file_path = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding(str_replace('*', '/', $file_name), "SJIS", "AUTO").'/';
                    $this->Zip->read_dir($file_path, FALSE);
                    if($this->Zip->zipdata){
                        $this->Zip->download(mb_convert_encoding($folname, "SJIS", "AUTO").'.zip');
                        //echo '<script>alert("'.str_replace('*', '/', $file_name).'.zip");parent.$.colorbox.close();</script>';
                    }else{
                        echo '<script>alert("フォルダが空です。");parent.$.colorbox.close();</script>';
                    }
                }else{
                    echo '<script>alert("該当のフォルダが存在しません。");parent.$.colorbox.close();</script>';
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxdownfile//ok
 */
    public function ajaxdownfile($file_name = null) {
        $this->autoRender = FALSE;
        if($file_name) {
            if (strpos($file_name, '..') !== false) {
                echo '<script>alert("該当のファイルが存在しません。");parent.$.colorbox.close();</script>';
            }else{
                $file_path = USER_DATA_URL.$this->authData['randid'].'/'.str_replace('*', '/', $file_name);
                if(file_exists(mb_convert_encoding($file_path, "SJIS", "AUTO"))){

                    $this->response->file($file_path);
                    $filename2 = strrchr( $file_path, "/" );
                    $filename3 = substr( $filename2, 1 );
                    // 単にダウンロードさせる場合はこれを使う
                    $this->response->download($filename3);
                }else{
                    echo '<script>alert("該当のファイルが存在しません。");parent.$.colorbox.close();</script>';
                }
            }        
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxcopy//ok
 */
    public function ajaxcopy() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $fptr = $this->data['name'];
            $fptr2 = $this->data['fname'];
            $fptr3 = $this->data['dname'];
            if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)) {
                return "direrr";
            }else{
                $file_path = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");
                $file_path2 = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($fptr3, "SJIS", "AUTO").'_esc'.mb_convert_encoding($fptr2, "SJIS", "AUTO");
                if(!file_exists($file_path)){
                    return "not";
                }else{
                    rename( $file_path, $file_path2 );
                    $file = new File($file_path2);
                    $reg="/(.*)(?:\.([^.]+$))/";
                    preg_match($reg,$fptr2,$retArr);
                    $fileNameprt2 = mb_convert_encoding($retArr[1], "SJIS", "AUTO");
                    $extension = pathinfo($file_path2, PATHINFO_EXTENSION);
                    $fileNameprt3 = mb_convert_encoding($fileNameprt2, "UTF-8", "SJIS").' -コピー';
                    $fileNameprt2 .= mb_convert_encoding(' -コピー', "SJIS", "AUTO");

                    $i = 1;
                    $filename = $fileNameprt2.".".$extension;
                    $filename2 = $fileNameprt3.".".$extension;
                    while(file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename)){
                        $filename = $fileNameprt2." (".$i.").".$extension;
                        $filename2 = $fileNameprt3." (".$i.").".$extension;
                        $i++;
                    }
                    if($file->copy(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename,true)) {
                        rename( $file_path2, $file_path );

                        $backdata['User']['id'] = $this->authData['id'];
                        $backdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                        // 登録するフィールド
                        $fields = array('precapa');

                        if ($this->User->save($backdata, false, $fields)) {

                            $newsdata['News']['title'] = $fptr.'のコピーを作成しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                echo $filename2;   //echoでもOK
                            }
                        }
                    }else{
                        rename( $file_path2, $file_path );
                        return "err";
                    }
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxfcopy//ok
 */
    public function ajaxfcopy() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $fptr = $this->data['name'];
            $fptr2 = $this->data['fname'];
            $fptr3 = $this->data['dname'];
            if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)) {
                return "direrr";
            }else{
                $file_path = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO").'/';
                if(!file_exists($file_path)){
                    return "not";
                }else{
                    $file = new Folder($file_path);
                    $fileNameprt3 = $fptr2.' -コピー';
                    $fileNameprt2 = mb_convert_encoding($fptr2.' -コピー', "SJIS", "AUTO");
                    $i = 1;
                    $filename = $fileNameprt2;
                    $filename2 = $fileNameprt3;
                    while(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .$filename.'/')){
                        $filename = $fileNameprt2." (".$i.")";
                        $filename2 = $fileNameprt3." (".$i.")";
                        $i++;
                    }
                    if($file->copy(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename.'/',true)) {

                        $backdata['User']['id'] = $this->authData['id'];
                        $backdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                        // 登録するフィールド
                        $fields = array('precapa');

                        if ($this->User->save($backdata, false, $fields)) {

                            $newsdata['News']['title'] = $fptr.'のコピーを作成しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                echo $filename2;
                            }
                        }
                    }else{
                        return "err";
                    }
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 *  ajaxrename//ok
 */
    public function ajaxrename() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if ((strpos($this->data['dname'], '..') !== false)||(strpos($this->data['afname'], '..') !== false)||(strpos($this->data['bename'], '..') !== false)) {
                return "direrr";
            }else{
                $file_path = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").mb_convert_encoding($this->data['bename'], "SJIS", "AUTO");
                if(!file_exists($file_path)){
                    return "not";
                }else{
                    if($this->data['dtype']=='file'){
                        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                        $i = 1;
                        $filename = $this->data['afname'].".".$extension;
                        while(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .$filename)){
                            $i++;
                            $filename = $this->data['afname']." (".$i.").".$extension;
                        }
                    }else if($this->data['dtype']=='fol'){
                        $i = 1;
                        $filename = $this->data['afname'];
                        while(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .$filename)){
                            $i++;
                            $filename = $this->data['afname']." (".$i.")";
                        }
                    }
                    $file_path2 = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").mb_convert_encoding($filename, "SJIS", "AUTO");
                    if(rename( $file_path, $file_path2 )) {

                        $newsdata['News']['title'] = $this->data['bename'].'を'.$filename.'に変更しました。';
                        $newsdata['News']['user_id'] = $this->authData['id'];

                        // 登録するフィールド
                        $fields2 = array('title','user_id');

                        if ($this->News->save($newsdata, false, $fields2)) {
                            echo $filename;   //echoでもOK
                        }

                    }else{
                        return "err";
                    }
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 *  ajaxopen//ok
 */
    public function ajaxopen() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if(strpos($this->data['name'], '..') !== false) {
                return "direrr";
            }else{
                if(file_exists(USER_DATA_URL.$this->authData['randid'].'/' .mb_convert_encoding($this->data['name'], "SJIS", "AUTO"))){
                    $file_path = USER_DATA_URL.$this->authData['randid'].'/'.$this->data['name'];
                    echo $file_path;
                }else{
                    return "err1";
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxtrash//ok
 */
    public function ajaxtrash() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if (strpos($this->data['name'], '..') !== false) {
                return "direrr";
            }else{
                $file_path = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO");
                $file_path2 = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").'_esc'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
                if(!file_exists($file_path)){
                    return "not";
                }else{
                    rename( $file_path, $file_path2 );

                    $file = new File($file_path2);
                    if($file->delete()){

                        $trashdata['User']['id'] = $this->authData['id'];
                        $trashdata['User']['precapa'] = $this->User->dir_size(USER_DATA_URL.$this->authData['randid'].'/');

                        // 登録するフィールド
                        $fields = array('precapa');

                        if ($this->User->save($trashdata, false, $fields)) {

                            $newsdata['News']['title'] = $this->data['name'].'を削除しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                return "ok";
                            }
                        }
                    }else{
                        rename( $file_path2, $file_path );
                        return "err";
                    }
                }
            }

        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxchange//ok
 */
    public function ajaxchange() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $file_path = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO");
            $file_path2 = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").'_esc'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
            if ((strpos($this->data['name'], '..') !== false)||(strpos($this->data['dname'], '..') !== false)||(strpos($this->data['fname'], '..') !== false)) {
                return "direrr";
            }else{
                if(!file_exists($file_path)){
                    return "not";
                }else{
                    rename( $file_path, $file_path2);
                    $file = new File($file_path2);
                    $backurl = BACK_IMG_URL.$this->authData['randid'].'/_'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
                    $backurl2 = BACK_IMG_URL.$this->authData['randid'].'/_'.$this->data['fname'];
                    if($file->copy($backurl,true)){
                        rename( $file_path2, $file_path );

                        $backdata['User']['id'] = $this->authData['id'];
                        $backdata['User']['backimg'] = $backurl2;

                        // 登録するフィールド
                        $fields = array('backimg');

                        if ($this->User->save($backdata, false, $fields)) {
                            $file1 = new File(mb_convert_encoding($this->authData['backimg'], "SJIS", "AUTO"));
                            $file1->delete();

                            $newsdata['News']['title'] = '背景を変更しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                return $backurl2;
                            }

                        }else{
                            return "err";
                        }

                    }else{
                        rename( $file_path2, $file_path );
                        return "err";
                    }
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
/**
 * ajaxmovetop//ok
 */
    public function ajaxmovetop() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $fptr = $this->data['id'];
            $fptr2 = $this->data['fname'];
            $fptr3 = $this->data['dname'];
            $fptr4 = $this->data['dname2'];
            if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)||(strpos($fptr4, '..') !== false)) {
                return "direrr";
            }else{
                if($this->data['ftype']=='file'){

                    $file_path = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");
                    if((!file_exists($file_path))||(!file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO")))){
                        return "not";
                    }else{
                        $reg="/(.*)(?:\.([^.]+$))/";
                        preg_match($reg,$fptr2,$retArr);
                        $fileNameprt2 = mb_convert_encoding($retArr[1], "SJIS", "AUTO");
                        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                        $fileNameprt3 = mb_convert_encoding($fileNameprt2, "UTF-8", "SJIS");

                        $i = 1;
                        $filename = $fileNameprt2.".".$extension;
                        $filename2 = $fileNameprt3.".".$extension;
                        while(file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename)){
                            $filename = $fileNameprt2." (".$i.").".$extension;
                            $filename2 = $fileNameprt3." (".$i.").".$extension;
                            $i++;
                        }
                        $file_path3 = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename;

                        if(rename( $file_path, $file_path3 )) {

                            $newsdata['News']['title'] = $fptr.'を移動しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                echo $filename2;   //echoでもOK
                            }
                        }else{
                            return "err";
                        }
                    }
                }else if($this->data['ftype']=='fol'){

                    $file_path = USER_DATA_URL.$this->authData['randid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");
                    if((!file_exists($file_path))||(!file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO")))){
                        return "not";
                    }else{

                        $fileNameprt2 = mb_convert_encoding($fptr2, "SJIS", "AUTO");
                        $fileNameprt3 = mb_convert_encoding($fileNameprt2, "UTF-8", "SJIS");

                        $i = 1;
                        $filename = $fileNameprt2;
                        $filename2 = $fileNameprt3;
                        while(file_exists(USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename)){
                            $filename = $fileNameprt2." (".$i.")";
                            $filename2 = $fileNameprt3." (".$i.")";
                            $i++;
                        }
                        $file_path3 = USER_DATA_URL.$this->authData['randid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename;

                        if(rename( $file_path, $file_path3 )) {

                            $newsdata['News']['title'] = $fptr.'を移動しました。';
                            $newsdata['News']['user_id'] = $this->authData['id'];

                            // 登録するフィールド
                            $fields2 = array('title','user_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                echo $filename2;   //echoでもOK
                            }
                        }else{
                            return "err";
                        }
                    }
                }else{
                    return "err";
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    public function gchan($param1 = "") {
        //$this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($param1 == 'jpn'){

            $backdata['User']['id'] = $this->authData['id'];
            $backdata['User']['langid'] = '1';

            // 登録するフィールド
            $fields = array('langid');

            if ($this->User->save($backdata, false, $fields)) {
                $newsdata['News']['title'] = '言語を変更しました。';
                $newsdata['News']['user_id'] = $this->authData['id'];

                // 登録するフィールド
                $fields2 = array('title','user_id');

                if ($this->News->save($newsdata, false, $fields2)) {
                    $this->redirect(array('controller'=>'users','action' => 'usertop'));
                }

            }else{
                $this->redirect(array('controller'=>'users','action' => 'usertop'));
            }
        }else if($param1 == 'eng'){
            
            $backdata['User']['id'] = $this->authData['id'];
            $backdata['User']['langid'] = '2';

            // 登録するフィールド
            $fields = array('langid');

            if ($this->User->save($backdata, false, $fields)) {
                $newsdata['News']['title'] = '言語を変更しました。';
                $newsdata['News']['user_id'] = $this->authData['id'];

                // 登録するフィールド
                $fields2 = array('title','user_id');

                if ($this->News->save($newsdata, false, $fields2)) {
                    $this->redirect(array('controller'=>'users','action' => 'usertop'));
                }

            }else{
                $this->redirect(array('controller'=>'users','action' => 'usertop'));
            }
        }else{
            $this->redirect(array('controller'=>'users','action' => 'usertop'));
        }
        
    }
}
