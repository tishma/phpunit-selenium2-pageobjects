<?php

abstract class PHPUnit_Extensions_Selenium2PageObject
{

	/**
	 * The testcase that is utilizing the page object.
	 *
	 * @var PHPUnit_Extensions_Selenium2TestCase
	 */
	protected $se;
	/**
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * @var array
	 */
	protected $map = array();

	/**
	 * Instantiate the PageObject and validate that the browser in a valid
	 * state for this PageObject.
	 *
	 * @param PHPUnit_Extensions_SeleniumTestCase $driver
	 */
	public function __construct(PHPUnit_Extensions_Selenium2TestCase $test)
	{
		$this->se = $test;
		$this->configureMappings();
		$this->assertPreConditions();
		$this->assertMapConditions();
	}

	/**
	 * Configure mappings
	 */
	protected function configureMappings()
	{
		// Placeholder
	}

	/**
	 * Assert that all elements in $this->map are present on the page
	 */
	protected function assertMapConditions()
	{
		foreach ($this->map as $field => $locator) {
			$this->se->assertNotNull(
				$this->se->byCssSelector($locator),
				'Locator field "' . $field . '" is not present.');
		}
	}

	/**
	 * You may want to assert a page's header or title. If all you are testing
	 * is that a field is present on the page, add the locator to the $map.
	 */
	protected function assertPreConditions()
	{
		// Placeholder
	}

	/**
	 * Convert a *ByMap call to using the real locator string as stored in
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
		// Only intercept *ByMap calls
		if (substr($name, -5) == 'ByMap') {
			// trim off the ByMap
			$name = substr($name, 0, -5);

			$element = $this->se->byCssSelector($this->getLocator($arguments[0]));

			array_shift($arguments);

			return call_user_func_array(array($element, $name), $arguments);
		} else {
			return call_user_func_array(array($this->se, $name), $arguments);
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


