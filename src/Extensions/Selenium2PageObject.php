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

abstract class PHPUnit_Extensions_Selenium2PageObject
{

	/**
	 * The testcase that is utilizing the page object.
	 *
	 * @var PHPUnit_Extensions_Selenium2TestCase
	 */
	protected $test;

	/**
	 * The key to UI locator map
	 *
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * @var array
	 * @see PHPUnit_Extensions_Selenium2PageObject::assertMapConditions()
	 */
	protected $map = array();

	/**
	 * Instantiate the PageObject and validate that the browser in a valid
	 * state for this PageObject.
	 *
	 * @param PHPUnit_Extensions_Selenium2TestCase $test
	 */
	public function __construct(PHPUnit_Extensions_Selenium2TestCase $test)
	{
		$this->test = $test;
		$this->assertPreConditions();
		$this->assertMapConditions();
	}

	/**
	 * Assert that all elements in $this->map are present on the page
	 */
	protected function assertMapConditions()
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
	protected function assertPreConditions()
	{
	}

	public function byMap($name)
	{
		return $this->test->byCssSelector($this->getLocator($name));
	}

	/**
	 * Convert a *(Each)ByMap call to using the real locator string as stored in
	 * $this->map
	 *
	 * @example
	 * // In the class definition
	 * protected $map = array('title' => 'div#title')
	 *
	 * // In your PageObject
	 * public function getTitle()
	 * {
	 *	 return $this->getTextByMap('title');
	 * }
	 *
	 * // In your test
	 * 	public function setUpPage() {
	 * 		parent::setUpPage();
	 * 		$this->loginPage = new LoginPageObject($this);
	 * }
	 *
	 * public function testTitle() {
	 * 		$this->url('http://www.example.com/login');
	 * 		$this->loginPage->clickByMap('title');
	 * }
	 *
	 * @param string $name Method name
	 * @param array $arguments An array of arguments. The first one must be the
	 * map locator.
	 *
	 * @return mixed
	 */

	public function __call($name, $arguments)
	{
		// Apply function to each element
		if (substr($name, -9) == 'EachByMap') {
			$name = substr($name, 0, -9);
			$elements = $this->elements($this->using('css selector')->value($this->getLocator($arguments[0])));
			foreach ($elements as $element) {
				if ($name == 'select') {
					$element = $this->test->select($element);
				}
				return call_user_func_array(array($element, $name), $arguments);
			}
		// Apply function to individual element
		} else if (substr($name, -5) == 'ByMap') {
			//trim off the ByMap
			$name = substr($name, 0, -5);
			$element = $this->byMap($arguments[0]);
			if ($name == 'select') {
				$element = $this->test->select($element);
			}
			array_shift($arguments);
			return call_user_func_array(array($element, $name), $arguments);
		} else {
			return call_user_func_array(array($this->test, $name), $arguments);
		}
	}

	/**
	 * Get a map key's locator string
	 *
	 * @param string $map
	 * @return string
	 * @throws InvalidArgumentException If the $map does not exist.
	 */
	protected function getLocator($map)
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
	 */
	protected function addMapping($mapping_name, $mapping_target) {
		$this->map[$mapping_name] = $mapping_target;
	}

}
