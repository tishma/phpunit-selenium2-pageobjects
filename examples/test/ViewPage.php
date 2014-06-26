<?php

class ViewPage extends PHPUnit_Extensions_Selenium2PageObject_Model
{

	protected $map = array(
		'header' => '//h1[@id="title"]',
		'real_name' => '//td[@id="output_your_name"]',
		'gender' => '//td[@id="output_your_gender"]'
	);
	protected $modelSkip = array('gender', 'header');

	public function assertPreConditions()
	{
		$this->se->assertEquals('Viewing your data', $this->textByMap('header'));
	}

	public function assertEqualsModel($object, $message = '')
	{
		parent::assertEqualsModel($object, $message);

		$this->se->assertEquals(
			$object->getGenderString(),
			$this->textByMap('gender')
		);
	}

	public function getRealName()
	{
		return $this->textByMap('real_name');
	}

}
