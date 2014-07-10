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
 * @package PHPUnit_Selenium2_PageObjects\Tests
 */

/**
 * Class Selenium2PageObject_ModelTest
 */
class Selenium2PageObject_ModelTest extends PHPUnit_Framework_TestCase
{

	public function testSetByModel()
	{
		$se = new MockSelenium2TestCase();
		$se->elements = array('user_count');

		$model = new MockGetterSetter();
		$model->setUserCount(123);

		$m = new MockPageObjectModel($se);
		$m->setFromModel($model);

		$this->assertEquals(
			$model->getUserCount(),
			$m->getUserCount(),
			'The user_count should match.'
		);

		$m->assertEqualsModel($model, 'The user_count should match');
	}

	public function testSetByModelSkip()
	{
		$se = new MockSelenium2TestCase();
		$se->elements = array('user_count');

		$model = new MockGetterSetter();
		$model->setUserCount(123);

		$m = new MockPageObjectModel($se);
		$m->modelSkip[] = 'user_count';
		$m->setFromModel($model);

		$this->assertNotEquals(
			$model->getUserCount(),
			$m->getUserCount(),
			'Since the user_count is skipped, the mock page user_count should not equal the model user_count.'
		);

		$m->assertEqualsModel(
			$model,
			'Since the user_count is skipped, it should not fail validation.'
		);
	}

	public function testSetByField()
	{
		$m = new MockGetterSetter();
		MockPageObjectModel::setByField($m, 'user_count', 99);

		$this->assertEquals(
			99,
			$m->userCount,
			'setByField should correctly translate and access the setter method.'
		);
	}

	public function testGetByField()
	{
		$m = new MockGetterSetter();
		$m->userCount = 10;

		$val = MockPageObjectModel::getByField($m, 'user_count');

		$this->assertEquals(
			10,
			$val,
			'getByField should correctly translate and access the getter method.'
		);
	}

	public function testGetCamelField()
	{
		$this->assertEquals(
			'UserCount',
			MockPageObjectModel::getCamelField('user_count')
		);
	}

}
