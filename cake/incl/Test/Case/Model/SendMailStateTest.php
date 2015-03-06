<?php
App::uses('SendMailState', 'Model');

/**
 * SendMailState Test Case
 *
 */
class SendMailStateTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.send_mail_state',
		'app.send_mail'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SendMailState = ClassRegistry::init('SendMailState');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SendMailState);

		parent::tearDown();
	}

}
