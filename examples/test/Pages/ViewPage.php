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
 * Class ViewPage
 */
class ViewPage extends PHPUnit_Extensions_Selenium2PageObject
{
	/**
	 * @var array
	 */
	protected $map = array(
		'header' => '//h1[@id="title"]',
		'real_name' => '#output_your_name',
		'gender' => '#output_your_gender'
	);

	/**
	 * Assert pre conditions callback
	 */
	public function assertPreConditions()
	{
		$this->test->assertEquals('Viewing your data', $this->byMap('header')->text());
	}

	/**
	 * Gets the real name
	 *
	 * @return mixed
	 */
	public function getRealName()
	{
		return $this->byMap('real_name')->text();
	}

}
