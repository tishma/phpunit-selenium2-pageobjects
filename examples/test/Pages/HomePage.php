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
 * @package PHPUnit_Selenium2_PageObjects\Examples
 */

/**
 * Class HomePage
 */
class HomePage extends PHPUnit_Extensions_Selenium2PageObject
{
	/**
	 * The page URL
	 *
	 * Must be set per page individually.
	 * Should be a relative URL preferably.
	 * Set the base URL in your test(s).
	 *
	 * @var string
	 * @see PHPUnit_Extensions_Selenium2TestCase::setBrowserUrl
	 */
	protected $url = '/';

	/**
	 * The page title
	 *
	 * @var string
	 */
	protected $pageTitle = 'Enter your data';

	/**
	 * The key to UI locator map
	 *
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * The key can be any string.
	 * The locator on the other hand must be a CSS selector compatible locator.
	 * XPath is not supported, use the method byXPath instead.
	 *
	 * Remember the preferred selector order:
	 * id > name > css [> xpath]
	 *
	 * @var array
	 */
	protected $map = array(
		'header' => '#title',
		'real_name' => '#your_name',
		'gender' => '#gender',
		'save' => '#form_submit'
	);

	/**
	 * Set person data
	 *
	 * @param PersonModel $person
	 */
	public function setPerson(PersonModel $person)
	{
		$gender = $person->getGenderString();
		$this->setGender($gender);
		$name = $person->getRealName();
		$this->setRealName($name);
	}

	/**
	 * A callback method AFTER the on load assertions
	 *
	 * @return void
	 */
	public function _afterOnLoadAssertions()
	{
		$this->test->assertEquals('Example!', $this->_byMap('header')->text());
	}

	/**
	 * Set the real name
	 *
	 * @param string $value The real name.
	 * @return self
	 */
	public function setRealName($value)
	{
		$element = $this->_byMap('real_name');
		$element->value($value);

		return $this;
	}

	/**
	 * Set gender
	 *
	 * @param int $gender
	 * @return self
	 */
	public function setGender($gender)
	{
		$genderSelect = $this->_byMap('gender');
		$genderSelect = $this->test->select($genderSelect);
		$genderSelect->selectOptionByLabel($gender);

		return $this;
	}

	/**
	 * Click save and return the view page object
	 *
	 * @return ViewPage The view page object
	 */
	public function save()
	{
		$this->_byMap('save')->click();
		
		return new ViewPage($this->test);
	}

}
