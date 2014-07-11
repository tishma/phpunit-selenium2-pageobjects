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
 * Class PHPUnit_Extensions_Selenium2PageObject
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
	 * Should be a relative URL preferably
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * The page title
	 *
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * The key to UI locator map
	 *
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * @var array
	 * @see PHPUnit_Extensions_Selenium2PageObject::_assertMapConditions()
	 */
	protected $map = array();

	/**
	 * Create the PageObject and set the test/webdriver
	 *
	 * @param PHPUnit_Extensions_Selenium2TestCase $test
	 */
	public function __construct(PHPUnit_Extensions_Selenium2TestCase $test)
	{
		$this->test = $test;
	}

	/**
	 * Load the page and calls the callbacks
	 *
	 * @param null|string $url An optional URL to load.
	 * @return self
	 * @see PHPUnit_Extensions_Selenium2PageObject::assertPreConditions
	 * @see PHPUnit_Extensions_Selenium2PageObject::assertPageTitle
	 * @see PHPUnit_Extensions_Selenium2PageObject::_assertMapConditions
	 */
	public function load($url = null) {
		if (!empty($url)) {
			$this->url = $url;
		}
		$this->test->url($this->url);

		$this->_assertPreConditions();
		$this->assertPageTitle();
		$this->_assertMapConditions();

		return $this;
	}

	/**
	 * Assert the page title
	 *
	 * @return self
	 */
	public function assertPageTitle() {
		$this->test->assertEquals($this->pageTitle, $this->test->title());

		return $this;
	}

	/**
	 * Assert that all elements in $this->map are present on the page
	 *
	 * @return self
	 */
	protected function _assertMapConditions()
	{
		foreach ($this->map as $field => $locator) {
			$this->test->assertNotNull(
				$this->test->byCssSelector($locator),
				'Locator field "' . $field . '" is not present.');
		}
	}

	/**
	 * Callback method before asserting the map
	 *
	 * You may want to assert a page's header or title. If all you are testing
	 * is that a field is present on the page, add the locator to the $map.
	 */
	protected function _assertPreConditions()
	{
	}

	/**
	 * Returns an element through locator by map
	 *
	 * @param string $name The locator
	 * @return PHPUnit_Extensions_Selenium2TestCase_Element
	 * @todo COver by a test
	 */
	public function byMap($name)
	{
		return $this->test->byCssSelector($this->getLocator($name));
	}

	/**
	 * Get a map key's locator string
	 *
	 * @param string $map
	 * @return string
	 * @throws InvalidArgumentException If the $map does not exist.
	 */
	public function getLocator($map)
	{
		if (isset($this->map[$map])) {
			return $this->map[$map];
		}

		throw new InvalidArgumentException('Map ' . $map . ' is not a valid locator key.');
	}

	/**
	 * Add a mapping
	 *
	 * @param string $mapping_name
	 * @param string $mapping_target
	 * @return self
	 * @todo COver by a test
	 */
	protected function addMapping($mapping_name, $mapping_target) {
		$this->map[$mapping_name] = $mapping_target;

		return $this;
	}

}
