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
	 * The element map
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
	 * Assert pre conditions callback
	 *
	 * @return void
	 */
	public function assertPreConditions()
	{
		$this->test->assertEquals('Example!', $this->textByMap('header'));
	}

	/**
	 * Set the real name
	 *
	 * @param string $value The real name.
	 * @return self
	 */
	public function setRealName($value)
	{
		$element = $this->byMap('real_name');
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
		$genderSelect = $this->byMap('gender');
		$genderSelect = $this->select($genderSelect);
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
		$this->byMap('save')->click();
		
		return new ViewPage($this->test);
	}

}
