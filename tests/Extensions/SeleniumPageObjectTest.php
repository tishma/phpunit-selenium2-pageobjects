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
		$this->test->setBrowserUrl('http://localhost/');
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

		$returned = $this->page->load();
		$this->assertInstanceOf('ExamplePage', $returned);
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

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo('foo123.html'));

		$this->page->expects($this->once())
			->method('_assertIsLoaded');

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
	 * Tests the _assertUrl method
	 *
	 * @covers ::_assertUrl
	 * @return void
	 */
	public function testAssertUrl()
	{
		$this->page = $this->getMock(
			'ExamplePage',
			array(
				'_beforeOnLoadAssertions',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('url')
			->will($this->returnValue('http://localhost/foo123.html'));

		$this->page->load();
	}

	/**
	 * Tests the _assertUrl method with too many slashes
	 *
	 * @covers ::_assertUrl
	 * @return void
	 */
	public function testAssertUrlWithTooManySlashes()
	{
		$this->page = $this->getMock(
			'ExamplePageUrlWithTooManySlashes',
			array(
				'_beforeOnLoadAssertions',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->setBrowserUrl('http://localhost/////');

		$this->test->expects($this->any())
			->method('url')
			->will($this->returnValue('http://localhost/omg.wtf'));

		$this->page->load();
	}

	/**
	 * Tests the _assertUrl method with an absolute URL
	 *
	 * @covers ::_assertUrl
	 * @return void
	 */
	public function testAssertUrlAbsoluteUrl()
	{
		$this->page = $this->getMock(
			'ExamplePageAbsoluteUrl',
			array(
				'_beforeOnLoadAssertions',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('url')
			->will($this->returnValue('http://other.host/foobar.php'));

		$this->page->load();
	}

	/**
	 * Tests the _assertUrl method with parameter
	 *
	 * @covers ::_assertUrl
	 * @return void
	 */
	public function testAssertUrlWithParameter()
	{
		$this->page = $this->getMock(
			'ExamplePageAssertCustomUrl',
			array(
				'_beforeOnLoadAssertions',
				'_assertPageTitle',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('url')
			->will($this->returnValue('http://localhost/custom.file'));

		$this->page->assertCustomUrl();
	}

	/**
	 * Tests the method assertPageTitle
	 *
	 * @covers ::_assertPageTitle
	 * @return void
	 */
	public function testAssertPageTitle()
	{
		$this->page = $this->getMock(
			'ExamplePage',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Foo 123'));

		$this->page->load();
	}

	/**
	 * Tests the assertPageTitle method with parameter
	 *
	 * @covers ::_assertPageTitle
	 * @return void
	 */
	public function testAssertPageTitleWithParameter()
	{
		$this->page = $this->getMock(
			'ExamplePageAssertCustomPageTitle',
			array(
				'_beforeOnLoadAssertions',
				'_assertUrl',
				'_assertElementsPresent',
				'_afterOnLoadAssertions'
			),
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Custom title'));

		$this->page->assertCustomPageTitle();
	}

	/**
	 * Tests the _assertElementsPresent method
	 *
	 * @covers ::_assertElementsPresent
	 * @return void
	 */
	public function testAssertElementsPresent()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicAssertElementsPresent',
			null,
			array($this->test)
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', 'not_null'));

		$this->page->assertElementsPresentDefault();
	}

	/**
	 * Tests the _assertElementsPresent method with custom elements
	 *
	 * @covers ::_assertElementsPresent
	 * @return void
	 */
	public function testAssertElementsPresentCustomElements()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicAssertElementsPresent',
			null,
			array($this->test)
		);

		$this->test->expects($this->exactly(2))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null'));

		$this->page->assertElementsPresentCustomElements();
	}

	/**
	 * Tests the _assertElementsPresent method with elements to exclude
	 *
	 * @covers ::_assertElementsPresent
	 * @return void
	 */
	public function testAssertElementsPresentExcludeElements()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicAssertElementsPresent',
			null,
			array($this->test)
		);

		$this->test->expects($this->exactly(2))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null'));

		$this->page->assertElementsPresentExcludeElements();
	}

	/**
	 * Tests the _assertElementsPresent method with a field missing
	 *
	 * @expectedException PHPUnit_Framework_ExpectationFailedException
	 * @covers ::_assertElementsPresent
	 * @return void
	 */
	public function testAssertElementsPresentNonexistentElement()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicAssertElementsPresent',
			null,
			array($this->test)
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', null));

		$this->page->assertElementsPresentDefault();
	}

	/**
	 * Tests the _addElement method
	 *
	 * @covers ::_addElement
	 * @return void
	 */
	public function testAddElement()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->addElement('fieldFour', 'field_4');

		$this->assertEquals(
			array(
				'fieldOne' => 'field_1',
				'fieldTwo' => 'field_2',
				'fieldThree' => 'field_3',
				'fieldFour' => 'field_4',
			),
			PHPUnit_Framework_Assert::readAttribute($this->page, 'map')
		);
	}

	/**
	 * Tests the _byMap method
	 *
	 * @covers ::_byMap
	 * @return void
	 */
	public function testByMap()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('byCssSelector')
			->with($this->equalTo('field_2'))
			->will($this->returnValue('element'));

		$locator = $this->page->byMap('fieldTwo');
		$expected = 'element';

		$this->assertEquals($expected, $locator);
	}

	/**
	 * Tests the _getLocator method
	 *
	 * @covers ::_getLocator
	 * @return void
	 */
	public function testGetLocator()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$locator = $this->page->getLocator('fieldTwo');
		$expected = 'field_2';

		$this->assertEquals($expected, $locator);
	}

	/**
	 * Tests the _getLocator method with nonexistent locator
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::_getLocator
	 * @return void
	 */
	public function testGetLocatorNonexistentField()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->getLocator('does_not_exist');
	}

	/**
	 * Tests the _addElement method with null field
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::_addElement
	 * @return void
	 */
	public function testAddElementNullField()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->addElement(null, 'field_4');
	}

	/**
	 * Tests the _addElement method with null locator
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::_addElement
	 * @return void
	 */
	public function testAddElementNullLocator()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->addElement('fieldFour', null);
	}

	/**
	 * Tests the _removeElement method
	 *
	 * @covers ::_removeElement
	 * @return void
	 */
	public function testRemoveElement()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->removeElement('fieldTwo');

		$this->assertEquals(
			array(
				'fieldOne' => 'field_1',
				'fieldThree' => 'field_3',
			),
			PHPUnit_Framework_Assert::readAttribute($this->page, 'map')
		);
	}

	/**
	 * Tests the _removeElement method with nonexistent field
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::_removeElement
	 * @return void
	 */
	public function testRemoveElementNonexistent()
	{
		$this->page = $this->getMock(
			'ExamplePagePublicElementMethods',
			null,
			array($this->test)
		);

		$this->page->removeElement('never_heard_of');
	}

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

/**
 * Page with absolute URL set
 */
class ExamplePageAbsoluteUrl extends ExamplePage {

	protected $url = 'http://other.host/foobar.php';

}

/**
 * Page with URL with too many slashes
 */
class ExamplePageUrlWithTooManySlashes extends ExamplePage {

	protected $url = '////omg.wtf';

}

/**
 * Page with assertion of custom URL
 */
class ExamplePageAssertCustomUrl extends ExamplePage {

	public function assertCustomUrl() {
		$this->_assertUrl('custom.file');
	}

}

/**
 * Page with assertion of custom page title
 */
class ExamplePageAssertCustomPageTitle extends ExamplePage {

	public function assertCustomPageTitle() {
		$this->_assertPageTitle('Custom title');
	}

}

/**
 * Page with public assertElementsPresent calling methods
 *
 * This is done for testing only!
 */
class ExamplePagePublicAssertElementsPresent extends ExamplePage {

	public function assertElementsPresentDefault() {
		$this->_assertElementsPresent($this->map, $this->excludeElementsCheckOnLoad);
	}

	public function assertElementsPresentCustomElements() {
		$this->_assertElementsPresent(
			array('foo1' => 'bar1', 'foo2' => 'bar2')
		);
	}

	public function assertElementsPresentExcludeElements() {
		$this->_assertElementsPresent($this->map, array('fieldTwo'));
	}

}

/**
 * Page with public element methods
 *
 * This is done for testing only!
 */
class ExamplePagePublicElementMethods extends ExamplePage {

	public function byMap($field) {
		return $this->_byMap($field);
	}

	public function getLocator($field) {
		return $this->_getLocator($field);
	}

	public function addElement($field, $locator) {
		$this->_addElement($field, $locator);
	}

	public function removeElement($field) {
		$this->_removeElement($field);
	}

}
