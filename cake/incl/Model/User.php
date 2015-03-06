<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 */
class User extends AppModel {

    var $name = 'User';


/**
 * Validation rules
 */
	public $validate = array(
		'mail' => array(
			//入力有無
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'last' => true,
				'required' => true,
				'message' =>'<p class="err_mes">メールアドレスを入力して下さい。</p>',
			),
            // メールアドレスであること。
            'email' => array(
				'rule' => array( 'email', true),
				'last' => true,
				'required' => true,
				'message' => '<p class="err_mes">メールアドレスの形式が不正です。</p>'
			 ),
            // 一意性チェック
			'emailExists' => array(
				//'rule' => 'isUnique',
				'rule' => array('myIsUnique'),
				'last' => true,
				'required' => true,
				'message' => '<p class="err_mes">このメールアドレスはすでに使用されています。</p>'
			),


		),
		'password' => array(
			//入力有無
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'last' => true,
				'required' => true,
				'message' =>'<p class="err_mes">パスワードを入力して下さい。</p>',
			),
			//半角英数１文字以上のみ有効
			'custom'=>array(
				'rule'=>array('custom','/^[a-zA-Z0-9]{1,}$/i'),
				'last' => true,
				'required' => true,
				'message'=>'<p class="err_mes">パスワードは半角英数字のみ有効です。</p>',
			),
		),
		'randnum' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => '同意してください。',
			),
		),
		'check' => array(
			'rule'     => array('multiple', array('min' => 1)),
			'required' => true,
			'message'  => '<p class="err_mes">利用規約への同意が必要です。</p>',
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Status' => array(
			'className' => 'Status',
			'foreignKey' => 'status_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'GroupList' => array(
			'className' => 'GroupList',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'GroupMember' => array(
			'className' => 'GroupMember',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'News' => array(
			'className' => 'News',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	function myIsUnique($check){

		$results = $this->find('all', array(
			'conditions' => array(
				'User.mail' => $check['mail'],
				'status_id' => '2'
			),
		));

		if(sizeof($results) == 0){
			return true;
		}else{
			return false;
		}
	}
	
	function dir_size($dir){
	  $handle = opendir($dir);
	  $mas = '';
	  while ($file = readdir($handle)) {
		if ($file != '..' && $file != '.' && !is_dir($dir.'/'.$file)) {
		  $mas += filesize($dir.'/'.$file);
		} else if (is_dir($dir.'/'.$file) && $file != '..' && $file != '.') {
		  $mas += $this->dir_size($dir.'/'.$file);
		}
	  }
	  return $mas;
	}
	function byte_format($size, $dec=-1, $separate=false){
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		$digits = ($size == 0) ? 0 : floor( log($size, 1024) );
		 
		$over = false;
		$max_digit = count($units) -1 ;
	 
		if($digits == 0){
			$num = $size;
		} else if(!isset($units[$digits])) {
			$num = $size / (pow(1024, $max_digit));
			$over = true;
		} else {
			$num = $size / (pow(1024, $digits));
		}
		 
		if($dec > -1 && $digits > 0) $num = sprintf("%.{$dec}f", $num);
		if($separate && $digits > 0) $num = number_format($num, $dec);
		 
		return ($over) ? $num . $units[$max_digit] : $num . $units[$digits];
	}
        function hogeenc($str){
                foreach(array('UTF-8','SJIS','EUC-JP','ASCII','JIS') as $charcode){
                        if(mb_convert_encoding($str, $charcode, $charcode) == $str){
                                return $charcode;
                        }
                }

                return null;
        }
}
