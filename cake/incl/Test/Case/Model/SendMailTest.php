<?php
App::uses('SendMail', 'Model');

/**
 * SendMail Test Case
 *
 */
class SendMailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.send_mail',
		'app.send_mail_state'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SendMail = ClassRegistry::init('SendMail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SendMail);

		parent::tearDown();
	}

}
