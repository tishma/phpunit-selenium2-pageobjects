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

require_once '../../vendor/autoload.php';
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
		$this->url('/');
		
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
		//$r[] = array($person);

		$person = new PersonModel();
		$person->setRealName('Nina Arsenault');
		$person->setGender(PersonModel::G_OTHER);
		//$r[] = array($person);

		return $r;
	}
}
