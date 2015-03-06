<?php
App::uses('AppModel', 'Model');
/**
 * AuthLevel Model
 *
 * @property GroupMember $GroupMember
 */
class AuthLevel extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'GroupMember' => array(
			'className' => 'GroupMember',
			'foreignKey' => 'auth_level_id',
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

}
