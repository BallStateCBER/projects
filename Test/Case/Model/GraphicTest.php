<?php
App::uses('Graphic', 'Model');

/**
 * Graphic Test Case
 *
 */
class GraphicTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.graphic', 'app.release', 'app.partner', 'app.tag', 'app.releases_tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Graphic = ClassRegistry::init('Graphic');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Graphic);

		parent::tearDown();
	}

}
