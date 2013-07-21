<?php

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
		$this->se->assertEquals('Example!', $this->textByMap('header'));
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
		$this->sendKeysByMap('real_name', $value);
	}

	public function setGender($gender)
	{
		$this->selectByMap('gender', 'label=' . $gender);
	}

	public function save()
	{
		$this->clickByMap('save');
		
		return new ViewPage($this->se);
	}
}
