<?php
App::uses('AppModel', 'Model');
/**
 * SendMail Model
 *
 * @property SendMailState $SendMailState
 */
class SendMail extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SendMailState' => array(
			'className' => 'SendMailState',
			'foreignKey' => 'send_mail_state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
