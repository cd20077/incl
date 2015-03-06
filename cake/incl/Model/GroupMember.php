<?php
App::uses('AppModel', 'Model');
/**
 * GroupMember Model
 */
class GroupMember extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'GroupList' => array(
			'className' => 'GroupList',
			'foreignKey' => 'group_list_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AuthLevel' => array(
			'className' => 'AuthLevel',
			'foreignKey' => 'auth_level_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        /*
        * チャットをファイルに書き込む
        */
       function writeLog($str,$name,$file,$max){
          $bbsLog = file($file);
          $fp     = fopen($file,"w"); 
          flock($fp,2);
          $line = time().'<>'.$name.'<>'.$str."<>".date('Y/m/d H:i:s')."<>\n"; //最新ログを書き込む
          fputs($fp,$line);
          for($i=0; $i < count($bbsLog); $i++){ //既存ログを書き込む
             if( $i == $max) break; //指定件数になったら停止
             fputs($fp,$bbsLog[$i]);
          }
          flock($fp,3); 
          fclose($fp);
       }
}
