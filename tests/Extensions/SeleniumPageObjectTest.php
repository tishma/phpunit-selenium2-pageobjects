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
	protected $page;

	/**
	 * Prepare a Selenium2TestCase and Selenium2PageObjectmock
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();

		$this->test = $this->getMock(
			'PHPUnit_Extensions_Selenium2TestCase',
			array('url', 'title', 'byCssSelector')
		);
	}

	/**
	 * Tests the constructor without additional parameters
	 *
	 * @covers ::__construct
	 * @return void
	 */
	public function testConstructorWithoutAdditionalParameters() {
		$this->page = new ExamplePage($this->test);

		$this->assertEquals(
			'foo123.html',
			PHPUnit_Framework_Assert::readAttribute($this->page, 'url')
		);
		$this->assertEquals(
			'Foo 123',
			PHPUnit_Framework_Assert::readAttribute($this->page, 'pageTitle')
		);
		$this->assertEquals(
			array(
				'fieldOne' => 'field_1',
				'fieldTwo' => 'field_2',
				'fieldThree' => 'field_3',
			),
			PHPUnit_Framework_Assert::readAttribute($this->page, 'map')
		);
	}

	/**
	 *
	 * Tests the constructor with additional parameters
	 *
	 * @covers ::__construct
	 * @return void
	 */
	public function testConstructorWithAdditionalParameters() {
		$this->page = new ExamplePage(
			$this->test,
			'special_url',
			'special title'
		);

		$this->assertEquals(
			'special_url',
			PHPUnit_Framework_Assert::readAttribute($this->page, 'url')
		);
		$this->assertEquals(
			'special title',
			PHPUnit_Framework_Assert::readAttribute($this->page, 'pageTitle')
		);
		$this->assertEquals(
			array(
				'fieldOne' => 'field_1',
				'fieldTwo' => 'field_2',
				'fieldThree' => 'field_3',
			),
			PHPUnit_Framework_Assert::readAttribute($this->page, 'map')
		);
	}

	/**
	 * Tests the constructor when loadOnConstruct is enabled
	 *
	 * @covers ::__construct
	 * @return void
	 */
	public function testConstructorLoadOnConstruct() {
		$this->page = $this->getMockBuilder('ExamplePageLoadOnConstruct')
			->disableOriginalConstructor()
			->setMethods(array('load', '_assertIsLoaded'))
			->getMock();

		$this->page->expects($this->once())
			->method('load');
		$this->page->expects($this->never())
			->method('_assertIsLoaded');

		$this->page->__construct($this->test);
	}

	/**
	 * Tests the constructor when checkIsLoadedOnConstruct is enabled
	 *
	 * @covers ::__construct
	 * @return void
	 */
	public function testConstructorCheckIsLoadedOnConstruct() {
		$this->page = $this->getMockBuilder('ExamplePageCheckIsLoadedOnConstruct')
			->disableOriginalConstructor()
			->setMethods(array('load', '_assertIsLoaded'))
			->getMock();

		$this->page->expects($this->never())
			->method('load');
		$this->page->expects($this->once())
			->method('_assertIsLoaded');

		$this->page->__construct($this->test);
	}

	/**
	 * Tests the load method without parameter
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadWithoutParameter()
	{
		$this->page = $this->getMock(
			'ExamplePage',
			array('_assertIsLoaded'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo('foo123.html'));

		$this->page->expects($this->once())
			->method('_assertIsLoaded');

		$this->page->load();
	}

	/**
	 * Tests whether load does not thrown an exception when providing a null URL
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadWithNullUrl()
	{
		$this->page = $this->getMock(
			'ExamplePage',
			array('_assertIsLoaded'),
			array($this->test)
		);

		$this->page->load(null);
	}

	/**
	 * Tests load with URL passed
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadWithUrl()
	{
		$this->page = $this->getMock(
			'ExamplePagePresetNullUrl',
			array('_assertIsLoaded'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo('test_abc.html'));

		$this->page->expects($this->once())
			->method('_assertIsLoaded');

		$this->page->load('test_abc.html');
	}

	/**
	 * Tests whether load thrown an exception when a null URL is preset
	 *
	 * @expectedException UnexpectedValueException
	 * @covers ::load
	 * @return void
	 */
	public function testLoadWithPresetNullUrl()
	{
		$this->page = $this->getMock(
			'ExamplePagePresetNullUrl',
			array('_assertIsLoaded'),
			array($this->test)
		);

		$this->page->load();
	}

	/**
	 * Tests the _assertIsLoaded method
	 *
	 * @covers ::_assertIsLoaded
	 * @return void
	 */
	public function testAssertIsLoaded()
	{
		$this->page = $this->getMock(
			'ExamplePage',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->page->expects($this->once())
			->method('_beforeOnLoadAssertions');
		$this->page->expects($this->once())
			->method('_assertUrl');
		$this->page->expects($this->once())
			->method('_assertPageTitle');
		$this->page->expects($this->once())
			->method('_assertElementsPresent');
		$this->page->expects($this->once())
			->method('_afterOnLoadAssertions');

		$this->page->load();
	}

	/**
	 * Tests the _assertIsLoaded method when doNotCheckUrlOnLoad is enabled
	 *
	 * @covers ::_assertIsLoaded
	 * @return void
	 */
	public function testAssertIsLoadedDoNotCheckUrlOnLoad()
	{
		$this->page = $this->getMock(
			'ExamplePageDoNotCheckUrlOnLoad',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->page->expects($this->once())
			->method('_beforeOnLoadAssertions');
		$this->page->expects($this->never())
			->method('_assertUrl');
		$this->page->expects($this->once())
			->method('_assertPageTitle');
		$this->page->expects($this->once())
			->method('_assertElementsPresent');
		$this->page->expects($this->once())
			->method('_afterOnLoadAssertions');

		$this->page->load();
	}

	/**
	 * Tests the _assertIsLoaded method when doNotCheckPageTitleOnLoad is enabled
	 *
	 * @covers ::_assertIsLoaded
	 * @return void
	 */
	public function testAssertIsLoadedDoNotCheckPageTitleOnLoad()
	{
		$this->page = $this->getMock(
			'ExamplePageDoNotCheckPageTitleOnLoad',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->page->expects($this->once())
			->method('_beforeOnLoadAssertions');
		$this->page->expects($this->once())
			->method('_assertUrl');
		$this->page->expects($this->never())
			->method('_assertPageTitle');
		$this->page->expects($this->once())
			->method('_assertElementsPresent');
		$this->page->expects($this->once())
			->method('_afterOnLoadAssertions');

		$this->page->load();
	}

	/**
	 * Tests the _assertIsLoaded method when doNotCheckElementsOnLoad is enabled
	 *
	 * @covers ::_assertIsLoaded
	 * @return void
	 */
	public function testAssertIsLoadedDoNotCheckElementsOnLoad()
	{
		$this->page = $this->getMock(
			'ExamplePageDoNotCheckElementsOnLoad',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->page->expects($this->once())
			->method('_beforeOnLoadAssertions');
		$this->page->expects($this->once())
			->method('_assertUrl');
		$this->page->expects($this->once())
			->method('_assertPageTitle');
		$this->page->expects($this->never())
			->method('_assertElementsPresent');
		$this->page->expects($this->once())
			->method('_afterOnLoadAssertions');

		$this->page->load();
	}

	/**
	 * Tests whether load calls url with the default URL
	 *
	 * @covers ::load
	 * @return void
	 */
	/*public function testLoadCallsUrlWithDefault()
	{
		$expectedUrl = 'foo123.html';

		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo($expectedUrl));

		$this->page->load();
	}*/

	/**
	 * Tests whether load calls url with the URL given
	 *
	 * @covers ::load
	 * @return void
	 */
	/*public function testLoadCallsUrlWithUrlGiven()
	{
		$url = 'foo.html';

		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo($url));

		$this->page->load($url);
	}*/

	/**
	 * Tests whether load return value equals the page object
	 *
	 * @covers ::load
	 * @return void
	 */
	/*public function testLoadReturnsThis()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$returned = $this->page->load();
		$this->assertInstanceOf('MockPage', $returned);
	}*/

	/**
	 * Tests the method assertPageTitle
	 *
	 * @covers ::assertPageTitle
	 * @return void
	 */
	/*public function testAssertPageTitle()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Foo 123'));

		$this->page->assertPageTitle();
	}*/

	/**
	 * Tests whether assertPageTitle return value equals the page object
	 *
	 * @covers ::assertPageTitle
	 * @return void
	 */
	/*public function testAssertPageTitleReturnsThis()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Foo 123'));

		$returned =	$this->page->assertPageTitle();
		$this->assertInstanceOf('MockPage', $returned);
	}*/

	/**
	 * Tests the _assertMapConditions method
	 *
	 * @covers ::_assertMapConditions
	 * @return void
	 */
	/*public function test_assertMapConditions()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle'),
			array($this->test)
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', 'not_null'));

		$this->page->load();
	}*/

	/**
	 * Tests the _assertMapConditions method with a locator missing
	 *
	 * @e3xpectedExeption
	 * @covers ::_assertMapConditions
	 * @return void
	 */
	/*public function test_assertMapConditionsMissingLocator()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle'),
			array($this->test)
		);

		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', null));

		$this->page->load();
	}*/

	/**
	 * Tests the getLocator method
	 *
	 * @covers ::getLocator
	 * @return void
	 */
	/*public function testGetLocatorReturnsMapValue()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$result = $this->page->getLocator('fieldTwo');
		$expected = 'field_2';

		$this->assertEquals(
			$expected,
			$result,
			'Returned map key should match.'
		);
	}*/

	/**
	 * Tests the getLocator method with a missing locator
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::getLocator
	 * @return void
	 */
	/*public function testGetLocatorFailsIfMissing()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->page->getLocator('this-key-does-not-exist');
	}*/

}

/**
 * Example page with the mandatory fields set
 */
class ExamplePage extends PHPUnit_Extensions_Selenium2PageObject {

	protected $url = 'foo123.html';

	protected $pageTitle = 'Foo 123';

	protected $map = array(
		'fieldOne' => 'field_1',
		'fieldTwo' => 'field_2',
		'fieldThree' => 'field_3',
	);

}

/**
 * Page with $loadOnConstruct enabled
 */
class ExamplePageLoadOnConstruct extends ExamplePage {

	protected $loadOnConstruct = true;

}

/**
 * Page with $checkIsLoadedOnConstruct enabled
 */
class ExamplePageCheckIsLoadedOnConstruct extends ExamplePage {

	protected $checkIsLoadedOnConstruct = true;

}

/**
 * Class MockPage
 */
class ExamplePagePresetNullUrl extends ExamplePage {

	protected $url = null;

}

/**
 * Page with $doNotCheckUrlOnLoad enabled
 */
class ExamplePageDoNotCheckUrlOnLoad extends ExamplePage {

	protected $doNotCheckUrlOnLoad = true;

}

/**
 * Page with $doNotCheckPageTitleOnLoad enabled
 */
class ExamplePageDoNotCheckPageTitleOnLoad extends ExamplePage {

	protected $doNotCheckPageTitleOnLoad = true;

}

/**
 * Page with $doNotCheckElementsOnLoad enabled
 */
class ExamplePageDoNotCheckElementsOnLoad extends ExamplePage {

	protected $doNotCheckElementsOnLoad = true;

}
