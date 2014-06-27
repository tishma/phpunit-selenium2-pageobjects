<?php

require_once __DIR__ . '/../src/Extensions/Selenium2PageObject.php';
require_once __DIR__ . '/../src/Extensions/Selenium2PageObject/Model.php';

class MockPageObjectModel extends PHPUnit_Extensions_Selenium2PageObject_Model
{
	public $map = array('user_count' => 'user_count');
	public $modelSkip = array();

	public $userCount;

	public function getUserCount()
	{
		return $this->userCount;
	}

	public function setUserCount($value)
	{
		$this->userCount = $value;
	}

	public static function getByField($object, $field)
	{
		return parent::getByField($object, $field);
	}

	public static function setByField($object, $field, $value)
	{
		return parent::setByField($object, $field, $value);
	}

	public static function getCamelField($field)
	{
		return parent::getCamelField($field);
	}

}

class MockPageObject extends PHPUnit_Extensions_Selenium2PageObject
{

	public $map = array();
	public $preConditionsCalled = false;
	public $mapConditionsCalled = false;

	public function assertPreConditions()
	{
		$this->preConditionsCalled = true;

		parent::assertPreConditions();
	}

	public function assertMapConditions()
	{
		$this->mapConditionsCalled = true;

		parent::assertMapConditions();
	}

	public function getLocator($map)
	{
		return parent::getLocator($map);
	}

}

class MockGetterSetter
{
	public $userCount;

	public function getUserCount()
	{
		return $this->userCount;
	}

	public function setUserCount($value)
	{
		$this->userCount = $value;
	}
}

class MockSelenium2TestCase extends PHPUnit_Extensions_Selenium2TestCase
{
	public $elements = array();
	public $elementsChecked = array();

	public function byCssSelector($selector) {
		$this->elementsChecked[] = $selector;
		if (in_array($selector, $this->elements)) {
			$seleniumServerUrl = PHPUnit_Extensions_Selenium2TestCase_URL::fromHostAndPort('www.dummy.com', 80);
			$driver = new PHPUnit_Extensions_Selenium2TestCase_Driver($seleniumServerUrl);
			return new PHPUnit_Extensions_Selenium2TestCase_Element($driver, $seleniumServerUrl);
		} else {
			throw new PHPUnit_Framework_ExpectationFailedException('Element with CSS selector '.$selector.' does not exist');
		}
	}
}
