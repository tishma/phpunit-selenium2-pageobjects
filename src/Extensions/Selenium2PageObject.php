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
 * @package PHPUnit_Selenium2_PageObjects\Source
 */

/**
 * PageObject base class for PHPUnit Selenium2 Test Cases
 *
 * The goal of this class is to provide common stuff about
 * Page objects and to help you DRY.
 * As it is bound to PHPUnit's Selenium2 Test cases,
 * it tries not to be a general purpose page object.
 * Also it tries to enforce the idea of keeping any Selenium
 * code within the page object and out of the tests.
 *
 * Every HTML page consists of the following:
 * - a URL
 * - a page title
 * - (0..n) elements
 *
 * Because of this and even though you generally should not
 * include any assertions in your page objects,
 * there are methods implemented asserting them.
 *
 * @link http://docs.seleniumhq.org/docs/06_test_design_considerations.jsp#page-object-design-pattern
 * @link http://martinfowler.com/bliki/PageObject.html
 * @link http://phpunit.de/manual/current/en/selenium.html#selenium.selenium2testcase
 */
abstract class PHPUnit_Extensions_Selenium2PageObject
{
	/**
	 * The test case that is utilizing the page object.
	 *
	 * @var PHPUnit_Extensions_Selenium2TestCase
	 */
	protected $test;

	/**
	 * The page URL
	 *
	 * Should be a relative URL preferably.
	 * Set the base URL in the test(s).
	 *
	 * @var string
	 * @see PHPUnit_Extensions_Selenium2TestCase::setBrowserUrl
	 */
	protected $url;

	/**
	 * Disable asserting of the URL on load
	 *
	 * @var bool
	 */
	protected $doNotCheckUrlOnLoad = false;

	/**
	 * The page title
	 *
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * Disable asserting of the page title on load
	 *
	 * @var bool
	 */
	protected $doNotCheckPageTitleOnLoad = false;

	/**
	 * The key to UI locator map
	 *
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * @var array
	 * @see _assertElementsPresent::_assertElements()
	 * @link
	 */
	protected $map = array();

	/**
	 * Disable asserting of the elements on load
	 *
	 * @var bool
	 */
	protected $doNotCheckElementsOnLoad = false;

	/**
	 * Exclude elements from asserting on load
	 *
	 * @var array
	 */
	protected $excludeElementsCheckOnLoad = array();

	/**
	 * Create the PageObject and set the test/WebDriver
	 *
	 * Creating a page does not load it as it is not always wanted.
	 *
	 * @param PHPUnit_Extensions_Selenium2TestCase $test The test with the WebDriver
	 * @param null|string $url An optional URL.
	 * @param null|string $pageTitle An optional page title.
	 */
	public function __construct(PHPUnit_Extensions_Selenium2TestCase $test, $url = null, $pageTitle = null)
	{
		$this->test = $test;

		if ($url) {
			$this->url = $url;
		}

		if ($pageTitle) {
			$this->pageTitle = $pageTitle;
		}
	}

	/**
	 * Load the page and calls the callbacks
	 *
	 * Traditionally the set URl, page title and all mapped elements
	 * should be present after loading the page.
	 * But since this is not ALWAYS the case, we can disable these checks
	 * or can exclude some elemnets from the map to be asserted.
	 *
	 * If you need to do something before or after the on load assertions,
	 * you can do so in _beforeOnLoadAssertions and _afterOnLoadAssertions
	 * respecitvely.
	 *
	 * @param null|string $url An optional URL to load.
	 * @return self
	 * @see PHPUnit_Extensions_Selenium2PageObject::_assertPreConditions
	 * @see PHPUnit_Extensions_Selenium2PageObject::_assertPageTitle
	 * @see PHPUnit_Extensions_Selenium2PageObject::_assertPageTitle
	 */
	public function load($url = null) {
		if (!empty($url)) {
			$this->url = $url;
		}
		$this->test->url($this->url);

		$this->_beforeOnLoadAssertions();

		if (!$this->doNotCheckUrlOnLoad) {
			$this->_assertUrl();
		}
		if (!$this->doNotCheckPageTitleOnLoad) {
			$this->_assertPageTitle();
		}
		if (!$this->doNotCheckElementsOnLoad) {
			$this->_assertElementsPresent(null, $this->excludeElementsCheckOnLoad);
		}

		$this->_afterOnLoadAssertions();

		return $this;
	}

	/**
	 * A callback method BEFORE the on load assertions
	 *
	 * @return void
	 */
	protected function _beforeOnLoadAssertions()
	{
	}

	/**
	 * A callback method AFTER the on load assertions
	 *
	 * @return void
	 */
	protected function _afterOnLoadAssertions()
	{
	}

	/**
	 * Assert the URL
	 *
	 * @param null|string $url An optional URL to assert.
	 * @return void
	 */
	protected function _assertUrl($url = null) {
		if ($url) {
			$this->url = $url;
		}
		$this->test->assertEquals($this->url, $this->test->url());
	}

	/**
	 * Assert the page title
	 *
	 * @param null|string $pageTitle An optional page title.
	 * @return void
	 */
	protected function _assertPageTitle($pageTitle = null) {
		if ($pageTitle) {
			$this->pageTitle = $pageTitle;
		}
		$this->test->assertEquals($this->pageTitle, $this->test->title());
	}

	/**
	 * Assert that all elements in $this->map are present on the page
	 *
	 * @param array $elements A elements maps to assert.
	 * @param array $excludeElements An optoinal list of field names to exclude.
	 * @return void
	 */
	protected function _assertElementsPresent($elements, $excludeElements = null)
	{
		// Exclude elements from elements map to check
		foreach ($excludeElements as $excludeField) {
			if (isset($elements[$excludeField])) {
				unset($elements[$excludeField]);
			}
		}

		foreach ($elements as $field => $locator) {
			$this->test->assertNotNull(
				$this->test->byCssSelector($locator),
				'Locator field "' . $field . '" is not present.'
			);
		}
	}

	/**
	 * Returns an element locator through its field name by map
	 *
	 * @param string $field The fiel name.
	 * @return PHPUnit_Extensions_Selenium2TestCase_Element The element.
	 */
	protected function _byMap($field)
	{
		return $this->test->byCssSelector($this->_getLocator($field));
	}

	/**
	 * Get the locator string of a mapped field
	 *
	 * @param string $key The key to a locator
	 * @return string The HTML locator.
	 * @throws InvalidArgumentException If the $key does not exist.
	 */
	protected function _getLocator($key)
	{
		if (isset($this->map[$key])) {
			return $this->map[$key];
		}

		throw new InvalidArgumentException('Map ' . $key . ' is not a valid locator key.');
	}

	/**
	 * Add an element mapping
	 *
	 * @param string $field The field field to map.
	 * @param string $locator The HTML locator.
	 * @return void
	 */
	protected function _addElement($field, $locator) {
		$this->map[$field] = $locator;
	}

	/**
	 * Remove an element mapping
	 *
	 * @param string $field The mapped field field.
	 * @return void
	 */
	protected function _removeElement($field)
	{
		if (isset($this->map[$field])) {
			unset($this->map[$field]);
		}
	}

}
