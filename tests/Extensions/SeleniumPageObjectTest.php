<?php
/**
 * Part of the PHPUnit Selenium2 PageObjects library
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Marc Würth <ravage@bluewin.ch>
 * @link https://github.com/ravage84/phpunit-selenium2-pageobjects
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package PHPUnit_Selenium2_PageObjects\Tests
 */

/**
 * Tests for Selenium2PageObject
 *
 * @coversDefaultClass PHPUnit_Extensions_Selenium2PageObject
 */
class Selenium2PageObjectTest extends PHPUnit_Framework_TestCase
{

	/**
	 * The mocked Selenium2TestCase
	 *
	 * @var PHPUnit_Extensions_Selenium2TestCase
	 */
	protected $test;

	/**
	 * The page object under test
	 *
	 * @var PHPUnit_Extensions_Selenium2PageObject
	 */
	protected $pageObject;

	/**
	 * Prepare a Selenium2TestCase and Selenium2PageObjectmock
	 */
	protected function setUp() {
		parent::setUp();

		$this->test = $this->getMock('PHPUnit_Framework_TestCase');
		$this->test = $this->getMock(
			'PHPUnit_Extensions_Selenium2PageObject',
			array(),
			array($this->test)
		);

	}

	/**
	 * Tests the constructor
	 *
	 * @covers ::__construct
	 */
	public function testConstructorCallsMapAndPreConditions()
	{
		$se = new MockSelenium2TestCase();

		$page = new MockPageObject($se);

		$this->assertTrue($page->preConditionsCalled,
			'assertPreConditions should be called when instantiated.');
	}

	/**
	 * Tests the assertMapConditions method
	 *
	 * @covers ::assertMapConditions
	 */
	public function testAssertMapConditionsChecksEachMapElement()
	{
		$se = new MockSelenium2TestCase();
		$se->elements[] = 'map_value';
		$se->elements[] = 'map_value2';

		$m = new MockPageObject($se);
		$m->map['map_key'] = 'map_value';
		$m->map['map_key2'] = 'map_value2';
		$m->assertMapConditions();

		$this->assertEquals(
			array('map_value', 'map_value2'),
			$se->elementsChecked,
			'Each map value passed should have been checked');
	}

	/**
	 * Tests the assertMapConditions with a map element missing
	 *
	 * @expectedException PHPUnit_Framework_ExpectationFailedException
	 * @covers ::assertMapConditions
	 */
	public function testAssertMissingMapElementFails()
	{
		$se = new MockSelenium2TestCase();
		$se->elements[] = 'map_value';

		$m = new MockPageObject($se);
		$m->map['map_key'] = 'map_value';
		$m->map['map_key2'] = 'map_value2';

		$m->assertMapConditions();
	}

	/**
	 * Tests the __call interceptor method
	 *
	 * @covers ::__call
	 * @todo Rewrite for Selenium2TestCase
	 */
	public function testCallsMadeByMapGetIntercepted()
	{
		$se = new MockSelenium2TestCase();
		$se->elements[] = 'map_value';

		$m = new MockPageObject($se);
		$m->map['map_key'] = 'map_value';

		$this->markTestIncomplete(
			'This test needs to be rewritten for Selenium2TestCase.'
		);

		try {
				$m->clickByMap('map_key');
		} catch (Exception $e) {
				$this->fail("Unable to 'click' on element");
		}

		$this->assertEquals(
			array('map_value'),
			$se->elementsChecked,
			'the __call method should translate the map key into the map value');
	}

	/**
	 * Tests the __call method without ByMap pass through
	 *
	 * @covers ::__call
	 */
	public function testCallsMadeWithoutByMapPassThrough()
	{
		$se = new MockSelenium2TestCase();
		$se->elements[] = 'map_value';

		$m = new MockPageObject($se);
		$m->map['map_key'] = 'map_value';

		$this->assertNotNull($m->ByCssSelector('map_value'));

		$this->assertEquals(
			array('map_value'),
			$se->elementsChecked,
			'the __call method should translate the map key into the map value');
	}

	/**
	 * Tests the getLocator method
	 *
	 * @covers ::getLocator
	 */
	public function testGetLocatorReturnsMapValue()
	{
		$se = new MockSelenium2TestCase();
		$m = new MockPageObject($se);
		$m->map['map_key'] = 'map_value';

		$this->assertEquals(
			'map_value',
			$m->getLocator('map_key'),
			'Retured map key should match exactly.');
	}

	/**
	 * Tests the getLocator method with a missing locator
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::getLocator
	 */
	public function testGetLocatorFailsIfMissing()
	{
		$se = new MockSelenium2TestCase();
		$m = new MockPageObject($se);
		$m->getLocator('this-key-does-not-exist');
	}

}
