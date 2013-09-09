<?php
App::uses('Partner', 'Model');

/**
 * Partner Test Case
 *
 */
class PartnerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.partner', 'app.release', 'app.tag', 'app.releases_tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Partner = ClassRegistry::init('Partner');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Partner);

		parent::tearDown();
	}

}
