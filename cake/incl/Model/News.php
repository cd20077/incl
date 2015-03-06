<?php
App::uses('AppModel', 'Model');
/**
 * News Model
 *
 * @property User $User
 * @property GroupList $GroupList
 */
class News extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
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
		)
	);
}
