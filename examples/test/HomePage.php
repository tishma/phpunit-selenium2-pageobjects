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

class HomePage extends PHPUnit_Extensions_Selenium2PageObject_Model
{
	protected $map = array(
		'header' => '#title',
		'real_name' => '#your_name',
		'gender' => '#gender',
		'save' => '#form_submit'
		);

	protected $modelSkip = array('gender', 'save', 'header');

	public function assertPreConditions()
	{
		$this->test->assertEquals('Example!', $this->textByMap('header'));
	}

	public function setFromModel($object)
	{
		parent::setFromModel($object);

		// Since `gender` on the model maps to an integer, override and set
		// by the gender string.
		$this->setGender($object->getGenderString());
	}

	public function setRealName($value)
	{
		//$this->valueByMap('real_name', $value);
		$element = $this->byMap('real_name');
		$element->value($value);
	}

	public function setGender($gender)
	{
		//$this->selectByMap('gender', 'label=' . $gender);
		$genderselect = $this->byMap('gender');
		$genderselect = $this->select($genderselect);
		$genderselect->selectOptionByLabel($gender);
	}

	public function save()
	{
		$this->clickByMap('save');
		
		return new ViewPage($this->test);
	}
}
