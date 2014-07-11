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

require_once __DIR__ . '/../vendor/autoload.php';


class MockSelenium2TestCase extends PHPUnit_Extensions_Selenium2TestCase
{
	public $elements = array();
	public $elementsChecked = array();

	public function byCssSelector($selector)
	{
		$this->elementsChecked[] = $selector;
		if (in_array($selector, $this->elements)) {
			$seleniumServerUrl = PHPUnit_Extensions_Selenium2TestCase_URL::fromHostAndPort('www.dummy.com', 80);
			$driver = new PHPUnit_Extensions_Selenium2TestCase_Driver($seleniumServerUrl);
			return new PHPUnit_Extensions_Selenium2TestCase_Element($driver, $seleniumServerUrl);
		} else {
			throw new PHPUnit_Framework_ExpectationFailedException('Element with CSS selector ' . $selector . ' does not exist');
		}
	}

}
