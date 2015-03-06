<?php
App::uses('AppController', 'Controller');
/**
 * Ajax2funcs Controller
 */
class Ajax2funcsController extends AppController {

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
 * index method
 */
    public function ajaxupfile() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        $this->GroupList->recursive = -1;

        $giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['gname']),'fields' => Array('id')));

        $dir = new Folder(PROJECT_DATA_URL.$this->data['gname'].'/');
        if($this->request->is('ajax')) {
            if($files = $dir->find($_FILES['file']['name'], true)){
                $fileNameprt = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $i = 1;
                $filename = $fileNameprt." (".$i.").".$extension;
                while(file_exists(PROJECT_DATA_URL.$this->data['gname'].'/' .$filename)){
                    $i++;
                    $filename = $fileNameprt." (".$i.").".$extension;
                }
                if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], PROJECT_DATA_URL.$this->data['gname'].'/' . mb_convert_encoding($filename, "SJIS", "AUTO"));

                    $backdata['GroupList']['id'] = $giddata['GroupList']['id'];
                    $backdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['gname'].'/');

                    // 登録するフィールド
                    $fields = array('precapa');

                    if ($this->GroupList->save($backdata, false, $fields)) {

                        $newsdata['News']['title'] = $filename.'をアップしました。';
                        $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                        // 登録するフィールド
                        $fields2 = array('title','group_list_id');

                        if ($this->News->save($newsdata, false, $fields2)) {
                            echo $filename;   //echoでもOK
                        }
                    }
                }else{
                    //echo "さん、こんにちは";   //echoでもOK
                }
            }else{
                if(is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], PROJECT_DATA_URL.$this->data['gname'].'/' . mb_convert_encoding($_FILES['file']['name'], "SJIS", "AUTO"));
                    $backdata['GroupList']['id'] = $giddata['GroupList']['id'];
                    $backdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['gname'].'/');

                    // 登録するフィールド
                    $fields = array('precapa');

                    if ($this->GroupList->save($backdata, false, $fields)) {

                        $newsdata['News']['title'] = $_FILES['file']['name'].'をアップしました。';
                        $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                        // 登録するフィールド
                        $fields2 = array('title','group_list_id');

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

	//ok
    public function ajaxupbackimg() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
			$giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['gname']),'fields' => Array('id','backimg')));

			$backurl = PROJECT_IMG_URL.$this->data['gname'].'/'.mb_convert_encoding($_FILES['file']['name'], "SJIS", "AUTO");
			
			if(is_uploaded_file($_FILES['file']['tmp_name'])) {
				move_uploaded_file($_FILES['file']['tmp_name'], $backurl);
				
				$backdata['GroupList']['id'] = $giddata['GroupList']['id'];
				$backdata['GroupList']['backimg'] = $backurl;
				
				// 登録するフィールド
				$fields = array('backimg');
				
				if ($this->GroupList->save($backdata, false, $fields)) {
					$file1 = new File($giddata['GroupList']['backimg']);
					$file1->delete();
					
					$newsdata['News']['title'] = '背景を変更しました。';
					$newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];
					
					// 登録するフィールド
					$fields2 = array('title','group_list_id');
					
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

	//ok
    public function ajaxappfol() {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            $giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')));
            $folname = $this->data['name'];
            if (strpos($folname, '..') !== false) {
                return "direrr";
            }else{
                if(file_exists(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($folname, "SJIS", "AUTO").'/')){
                    $i = 1;
                    $folprt = $folname;
                    $folname = $folprt." (".$i.")";
                    while(file_exists(PROJECT_DATA_URL.$this->data['id'].'/' .mb_convert_encoding($folname, "SJIS", "AUTO").'/')){
                        $i++;
                        $folname = $folprt." (".$i.")";
                    }
                }
                $dir = new Folder();
                if($dir->create(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($folname, "SJIS", "AUTO").'/')){

                    $newsdata['News']['title'] = $this->data['name'].'を作成しました。';
                    $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                    // 登録するフィールド
                    $fields2 = array('title','group_list_id');

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
                if(!file_exists(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO"))){
                    return "not";
                }else{
                    if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){
                        $folder = new Folder(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO").'/');
                        if($folder->delete()){
                            $trashdata['GroupList']['id'] = $giddata['GroupList']['id'];
                            $trashdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['id'].'/');

                            // 登録するフィールド
                            $fields = array('precapa');

                            if ($this->GroupList->save($trashdata, false, $fields)) {

                                $newsdata['News']['title'] = $this->data['name'].'を削除しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

                                if ($this->News->save($newsdata, false, $fields2)) {
                                    return "ok";
                                }
                            }
                        }else{
                            return "err";
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
	//ok
    public function ajaxdownfol($file_name = null,$ranid = null) {
        $this->autoRender = FALSE;
        if($file_name) {
            if (strpos($file_name, '..') !== false) {
                echo '<script>alert("該当のフォルダが存在しません。");parent.$.colorbox.close();</script>';
            }else{
                if(file_exists(PROJECT_DATA_URL.$ranid.DS.mb_convert_encoding(str_replace('*', '/', $file_name), "SJIS", "AUTO"))){
                    $filename2 = strrchr( "/".str_replace('*', '/', $file_name), "/" );//
                    $folname = substr( $filename2, 1 );//
                    $file_path = PROJECT_DATA_URL.$ranid.DS.mb_convert_encoding(str_replace('*', '/', $file_name), "SJIS", "AUTO").'/';
                    $this->Zip->read_dir($file_path, FALSE);
                    if($this->Zip->zipdata){
                        $this->Zip->download(mb_convert_encoding($folname, "SJIS", "AUTO").'.zip');
                    }else{
                        echo '<script>alert("フォルダが空です。");parent.$.colorbox.close();</script>';
                    }
                }else{
                    echo '<script>alert("フォルダが空です。");parent.$.colorbox.close();</script>';
                }
            }	
        }else{
                $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }

	//ok
    public function ajaxdownfile($file_name = null,$ranid = null) {
        $this->autoRender = FALSE;
        date_default_timezone_set('Asia/Tokyo');
        if($file_name && $ranid) {
            if (strpos($file_name, '..') !== false) {
                echo '<script>alert("該当のファイルが存在しません。");parent.$.colorbox.close();</script>';
            }else{
                $file_path = PROJECT_DATA_URL.$ranid.DS.str_replace('*', '/', $file_name);                
                if(file_exists(mb_convert_encoding($file_path, "SJIS", "AUTO"))){

                    $this->response->file($file_path);
                    $filename2 = strrchr( $file_path, "/" );
                    $filename3 = substr( $filename2, 1 );
                    // 単にダウンロードさせる場合はこれを使う
                    $this->response->download($filename3);
                }else{
                    echo '<script>alert("ファイルが存在しません。");parent.$.colorbox.close();</script>';
                }
            }
        }else{
                $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
	
    public function ajaxcopy() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){
                $fptr = $this->data['name'];
                $fptr2 = $this->data['fname'];
                $fptr3 = $this->data['dname'];
                if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)) {
                    return "direrr";
                }else{
                    $file_path = PROJECT_DATA_URL.$this->data['id'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");
                    $file_path2 = PROJECT_DATA_URL.$this->data['id'].DS.mb_convert_encoding($fptr3, "SJIS", "AUTO").'_esc'.mb_convert_encoding($fptr2, "SJIS", "AUTO");
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
                        while(file_exists(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename)){
                            $filename = $fileNameprt2." (".$i.").".$extension;
                            $filename2 = $fileNameprt3." (".$i.").".$extension;
                            $i++;
                        }
                        if($file->copy(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename,true)) {
                            rename( $file_path2, $file_path );

                            $backdata['GroupList']['id'] = $giddata['GroupList']['id'];
                            $backdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['id'].'/');
                            // 登録するフィールド
                            $fields = array('precapa');

                            if ($this->GroupList->save($backdata, false, $fields)) {

                                $newsdata['News']['title'] = $fptr.'のコピーを作成しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

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
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    public function ajaxfcopy() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){

                $fptr = $this->data['name'];
                $fptr2 = $this->data['fname'];
                $fptr3 = $this->data['dname'];
                if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)) {
                    return "direrr";
                }else{
                    $file_path = PROJECT_DATA_URL.$this->data['id'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO").'/';
                    if(!file_exists($file_path)){
                        return "not";
                    }else{
                        $file = new Folder($file_path);
                        $fileNameprt3 = $fptr2.' -コピー';
                        $fileNameprt2 = mb_convert_encoding($fptr2.' -コピー', "SJIS", "AUTO");
                        $i = 1;
                        $filename = $fileNameprt2;
                        $filename2 = $fileNameprt3;
                        while(file_exists(PROJECT_DATA_URL.$this->data['id'].'/' .$filename.'/')){
                            $filename = $fileNameprt2." (".$i.")";
                            $filename2 = $fileNameprt3." (".$i.")";
                            $i++;
                        }
                        if($file->copy(PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($fptr3, "SJIS", "AUTO").$filename.'/',true)) {

                            $backdata['GroupList']['id'] = $giddata['GroupList']['id'];
                            $backdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['id'].'/');

                            // 登録するフィールド
                            $fields = array('precapa');

                            if ($this->GroupList->save($backdata, false, $fields)) {

                                $newsdata['News']['title'] = $fptr.'のコピーを作成しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

                                if ($this->News->save($newsdata, false, $fields2)) {
                                    echo $filename2;   //echoでもOK
                                }
                            }
                        }else{
                            return "err";
                        }
                    }
                }
            }else{
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    public function ajaxrename() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if ((strpos($this->data['dname'], '..') !== false)||(strpos($this->data['afname'], '..') !== false)||(strpos($this->data['bename'], '..') !== false)) {
                return "direrr";
            }else{
                if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){
                    $file_path = PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").mb_convert_encoding($this->data['bename'], "SJIS", "AUTO");
                    if(!file_exists($file_path)){
                        return "not";
                    }else{
                        if($this->data['dtype']=='file'){
                            $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                            $i = 1;
                            $filename = $this->data['afname'].".".$extension;
                            while(file_exists(PROJECT_DATA_URL.$this->data['id'].'/' .$filename)){
                                $i++;
                                $filename = $this->data['afname']." (".$i.").".$extension;
                            }
                        }else if($this->data['dtype']=='fol'){
                            $i = 1;
                            $filename = $this->data['afname'];
                            while(file_exists(PROJECT_DATA_URL.$this->data['id'].'/' .$filename)){
                                $i++;
                                $filename = $this->data['afname']." (".$i.")";
                            }
                        }
                        $file_path2 = PROJECT_DATA_URL.$this->data['id'].DS.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").mb_convert_encoding($filename, "SJIS", "AUTO");
                        if(rename( $file_path, $file_path2 )) {

                            $newsdata['News']['title'] = $this->data['bename'].'を'.$filename.'に変更しました。';
                            $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                            // 登録するフィールド
                            $fields2 = array('title','group_list_id');

                            if ($this->News->save($newsdata, false, $fields2)) {
                                echo $filename;   //echoでもOK
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
	//ok
    public function ajaxopen() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if(strpos($this->data['name'], '..') !== false) {
                return "direrr";
            }else{
                if(file_exists(PROJECT_DATA_URL.$this->data['id'].'/' .mb_convert_encoding($this->data['name'], "SJIS", "AUTO"))){
                    $file_path = PROJECT_DATA_URL.$this->data['id'].'/'.$this->data['name'];
                    echo $file_path;
                }else{
                    return "err1";
                }
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }

	//ok
    public function ajaxtrash() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if (strpos($this->data['name'], '..') !== false) {
                return "direrr";
            }else{
                if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id')))){
                    $file_path = PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO");
                    $file_path2 = PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").'_esc'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
                    if(!file_exists($file_path)){
                        return "not";
                    }else{
                        rename( $file_path, $file_path2 );
                        $file = new File($file_path2);
                        if($file->delete()){

                            $trashdata['GroupList']['id'] = $giddata['GroupList']['id'];
                            $trashdata['GroupList']['precapa'] = $this->User->dir_size(PROJECT_DATA_URL.$this->data['id'].'/');

                            // 登録するフィールド
                            $fields = array('precapa');

                            if ($this->GroupList->save($trashdata, false, $fields)) {

                                $newsdata['News']['title'] = $this->data['name'].'を削除しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

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
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
	
	//ok
    public function ajaxchange() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['id']),'fields' => Array('id','backimg')))){
                $file_path = PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['name'], "SJIS", "AUTO");
                $file_path2 = PROJECT_DATA_URL.$this->data['id'].'/'.mb_convert_encoding($this->data['dname'], "SJIS", "AUTO").'_esc'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
                if ((strpos($this->data['name'], '..') !== false)||(strpos($this->data['dname'], '..') !== false)||(strpos($this->data['fname'], '..') !== false)) {
                    return "direrr";
                }else{
                    if(!file_exists($file_path)){
                        return "not";
                    }else{
                        rename( $file_path, $file_path2);
                        $file = new File($file_path2);
                        $backurl = PROJECT_IMG_URL.$this->data['id'].'/_'.mb_convert_encoding($this->data['fname'], "SJIS", "AUTO");
                        $backurl2 = PROJECT_IMG_URL.$this->data['id'].'/_'.$this->data['fname'];

                        if($file->copy($backurl,true)){
                            rename( $file_path2, $file_path );

                            $backdata['GroupList']['id'] = $giddata['GroupList']['id'];
                            $backdata['GroupList']['backimg'] = $backurl2;

                            // 登録するフィールド
                            $fields = array('backimg');

                            if ($this->GroupList->save($backdata, false, $fields)) {
                                $file1 = new File(mb_convert_encoding($giddata['GroupList']['backimg'], "SJIS", "AUTO"));
                                $file1->delete();

                                $newsdata['News']['title'] = '背景を変更しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

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
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }
    public function ajaxmovetop() {
        $this->autoRender = FALSE;
	date_default_timezone_set('Asia/Tokyo');
        if($this->request->is('ajax')) {
            if($giddata = $this->GroupList->find('first',Array('conditions' => Array('ranid' => $this->data['gid']),'fields' => Array('id')))){
                $fptr = $this->data['id'];
                $fptr2 = $this->data['fname'];
                $fptr3 = $this->data['dname'];
                $fptr4 = $this->data['dname2'];
                if ((strpos($fptr, '..') !== false)||(strpos($fptr2, '..') !== false)||(strpos($fptr3, '..') !== false)||(strpos($fptr4, '..') !== false)) {
                    return "direrr";
                }else{
                    if($this->data['ftype']=='file'){

                        $file_path = PROJECT_DATA_URL.$this->data['gid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");

                        if((!file_exists($file_path))||(!file_exists(PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO")))){
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
                            while(file_exists(PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename)){
                                $filename = $fileNameprt2." (".$i.").".$extension;
                                $filename2 = $fileNameprt3." (".$i.").".$extension;
                                $i++;
                            }
                            $file_path3 = PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename;

                            if(rename( $file_path, $file_path3 )) {

                                $newsdata['News']['title'] = $fptr.'を移動しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

                                if ($this->News->save($newsdata, false, $fields2)) {
                                    echo $filename2;   //echoでもOK
                                }

                            }else{
                                    return "err";
                            }
                        }
                    }else if($this->data['ftype']=='fol'){

                        $file_path = PROJECT_DATA_URL.$this->data['gid'].DS.mb_convert_encoding($fptr, "SJIS", "AUTO");
                        if((!file_exists($file_path))||(!file_exists(PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO")))){
                            return "not";
                        }else{

                            $fileNameprt2 = mb_convert_encoding($fptr2, "SJIS", "AUTO");
                            $fileNameprt3 = mb_convert_encoding($fileNameprt2, "UTF-8", "SJIS");

                            $i = 1;
                            $filename = $fileNameprt2;
                            $filename2 = $fileNameprt3;
                            while(file_exists(PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename)){
                                $filename = $fileNameprt2." (".$i.")";
                                $filename2 = $fileNameprt3." (".$i.")";
                                $i++;
                            }
                            $file_path3 = PROJECT_DATA_URL.$this->data['gid'].'/'.mb_convert_encoding($fptr4, "SJIS", "AUTO").$filename;

                            if(rename( $file_path, $file_path3 )) {

                                $newsdata['News']['title'] = $fptr.'を移動しました。';
                                $newsdata['News']['group_list_id'] = $giddata['GroupList']['id'];

                                // 登録するフィールド
                                $fields2 = array('title','group_list_id');

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
                return "err";
            }
        }else{
            $this->redirect(array('controller' => 'tops', 'action' => "index"));
        }
    }


}
