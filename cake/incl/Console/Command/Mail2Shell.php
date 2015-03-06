<?php
/*
 * コマンドからのメール送信モジュール
 * 
 */

require_once("Mail.php");
App::import('Vendor', 'dlib');
config('settings');

class Mail2Shell extends AppShell{
	var $uses = array('SendMail');
	

	function send(){
		
		$group_code = date('Ymd');

		// 送信対象のIDを取得（ロックのため）
		$send_mail_ids = $this->SendMail->find('list',array(
				'limit'=>SPI_SM_DEAL_MAX,
				'conditions'=>array(
					'send_mail_state_id'=>1,
					array('OR'=>
						array(
							array(
								'send_date'=>null,
								'send_time'=>null
							),
							array(
								'send_date'=>null,
								'send_time <='=>date('H:i:s')
							),
							array(
								'send_date <='=>date('Y-m-d'),
								'send_time'=>null
							),
							array(
								'send_date <='=>date('Y-m-d'),
								'send_time <='=>date('H:i:s')
							)
						)
					)
				),
				'fields'=>array('SendMail.id')
			)
		);
		$this->out('Date     :'.date('Y/m/d H:i:s'));
		$this->out('send list:');
		//$this->out(var_dump($send_mail_ids));


		$fields = array(
			'group_code'=>"'$group_code'",
			'send_mail_state_id'=>2
		);

		$conditions = array(
			'SendMail.id'=>array_values($send_mail_ids),
		);

		// 送信対象になったメール情報をロック
		$this->SendMail->updateAll($fields, $conditions);

		mb_language("Ja") ;
		mb_internal_encoding("UTF-8");

		// 送信対象の一覧の情報を再取得
		$mails = $this->SendMail->find('all', array(
			'conditions'=>array(
				'SendMail.id'=>array_values($send_mail_ids)
			)
		));

		$params = array(
			"host" => SPI_SM_HOST,
			"port" => SPI_SM_PORT,
			/*
			"auth" => false
			"host" => "smtp.gmail.com",   // SMTPサーバー名
			"port" => 587,              // ポート番号
			*/
			"auth" => true,            // SMTP認証を使用する
			"username" => SPI_SM_USERNAME,  // SMTPのユーザー名
			"password" => SPI_SM_PASSWORD   // SMTPのパスワード
		);

		$mailObject = Mail::factory("smtp", $params);

		foreach($mails as $mail){
			$recipients = $mail['SendMail']['to_address'];
			$headers = array(
				"To" => $mail['SendMail']['to_address'],
				"From" => $mail['SendMail']['from_address'],
				"Subject" => mb_encode_mimeheader($mail['SendMail']['subject'])
			);

			$message = mb_convert_encoding(mb_convert_kana($mail['SendMail']['body'],'KV', 'UTF-8'),'JIS','UTF-8');
//			$message = mb_convert_encoding($message, "SJIS-win", "JIS");
			$message = str_replace("\n", "\r\n", $message);
			$body = $message;

			$result = $mailObject->send($recipients, $headers, $body);
			$this->out('send state:');
			//$this->out(var_dump($result));
			$this->out('id  :'.$mail['SendMail']['id']);
			$this->out('send:'.$mail['SendMail']['to_address']);
			$data = array();
			$data['id']                 = $mail['SendMail']['id'];
			$data['finish_dt']          = '';
			$data['send_mail_state_id'] = 3;
			$data['server_ip']          = SPI_SM_HOST;
			$data['result']             = $result;
			$data['server_name']        = (empty($_SERVER['USERDOMAIN']))?@exec('hostname'):$_SERVER['USERDOMAIN'];
			// 完了のフラグをかけていく
			$this->SendMail->create();
			$this->SendMail->save(array('SendMail'=>$data));


			// 転送
			if(!empty($mail['SendMail']['fw_to'])){
				$recipients = $mail['SendMail']['fw_to'];
				$headers = array(
					"To" => $mail['SendMail']['fw_to'],
					"From" => $mail['SendMail']['from_address'],
					"Subject" => mb_encode_mimeheader('Fw:'.$mail['SendMail']['subject'])
				);
				$mailObject->send($recipients, $headers, $body);
			}
		}
		$this->out('SuccessFull');
		//echo $this->sql_dump();
	}

/**
   * クエリ表示
   */ 
	function sql_dump() 
	{ 
		if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) { 
			return false; 
		} 
		$noLogs = !isset($logs); 
		if ($noLogs): 
			$sources = ConnectionManager::sourceList(); 

			$logs = array(); 
			foreach ($sources as $source): 
				$db =& ConnectionManager::getDataSource($source); 
				if (!$db->isInterfaceSupported('getLog')): 
					continue; 
				endif; 
				$logs[$source] = $db->getLog(); 
			endforeach; 
		endif; 
     
		$tmp = ''; 
		if ($noLogs || isset($_forced_from_dbo_)){ 
			foreach ($logs as $source => $logInfo){ 
				$text = $logInfo['count'] > 1 ? 'queries' : 'query'; 
				$tmp .= sprintf("cakeSqlLog_%s ",preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))); 
				$tmp .= sprintf("(%s) %s %s took %s ms\n", $source, $logInfo['count'], $text, $logInfo['time']); 
				$tmp .= "Nr\tQuery\tError\tAffected\tNum. rows\tTook (ms)\n"; 
				foreach ($logInfo['log'] as $k => $i){ 
					$tmp .= sprintf("%s\t%s\t%s\t%s\t%s\t%s\t\n",($k + 1), $i['query'],$i['error'],$i['affected'],$i['numRows'],$i['took']); 
				} 
			} 
		}else{ 
			$tmp .= '<P>Encountered unexpected $logs cannot generate SQL log</P>'; 
		} 
		return $tmp; 
	} 


}
?>