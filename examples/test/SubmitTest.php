<?php

require_once 'HomePage.php';
require_once 'ViewPage.php';
require_once __DIR__ . '/../PersonModel.php';

class SubmitTest extends PHPUnit_Extensions_Selenium2TestCase
{
	public function setUp()
	{
		// Change the URL according to your setup
		$this->setBrowserUrl('http://localhost/phpunit-selenium2-pageobjects/examples/site/');
		$this->setBrowser('firefox');
	}

	/**
	 * Test submitting data to the home page (index)
	 *
	 * @dataProvider dataPeople
	 */
	public function testSubmit(PersonModel $person)
	{
		$this->open('/');
		
		$home = new HomePage($this);
		$home->setFromModel($person);
		$view = $home->save();

		$view->assertEqualsModel($person);
	}

	/**
	 * Data provider with people models
	 *
	 * @return array Person models
	 */
	public function dataPeople()
	{
		$r = array();

		$person = new PersonModel();
		$person->setRealName('Graham Christensen');
		$person->setGender(PersonModel::G_MALE);
		$r[] = array($person);

		$person = new PersonModel();
		$person->setRealName('Esley Svanas');
		$person->setGender(PersonModel::G_FEMALE);
		$r[] = array($person);

		$person = new PersonModel();
		$person->setRealName('Nina Arsenault');
		$person->setGender(PersonModel::G_OTHER);
		$r[] = array($person);

		return $r;
	}
}
