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
	 * @var string
	 */
	protected $url = 'view.php';

	/**
	 * The page title
	 *
	 * @var string
	 */
	protected $pageTitle = 'View your data';

	/**
	 * The key to UI locator map
	 *
	 * A mapping of unique keys to locator strings. Each one of these is
	 * validated to ensure it exists on the page when the PageObject is
	 * instantiated.
	 *
	 * The key can be any string.
	 * The locator on the other hand must be a CSS selector compatible locator.
	 * XPath is not supported, use the method byXPath instead.
	 *
	 * Remember the preferred selector order:
	 * id > name > css [> xpath]
	 *
	 * @var array
	 */
	protected $map = array(
		'header' => 'h1#title',
		'real_name' => '#output_your_name',
		'gender' => '#output_your_gender'
	);

	/**
	 * Shall it call the on load assertions when the page object gets constructed?
	 *
	 * Only relevant when $loadOnConstruct = false.
	 *
	 * @var bool
	 */
	protected $checkIsLoadedOnConstruct = true;

	/**
	 * A callback method AFTER the on load assertions
	 *
	 * @return void
	 */
	protected function _afterOnLoadAssertions() {
		$this->test->assertEquals('Viewing your data', $this->_byMap('header')->text());
	}

	/**
	 * Gets the real name
	 *
	 * @return string The real name.
	 */
	public function getRealName()
	{
		return $this->_byMap('real_name')->text();
	}

	/**
	 * Gets the gender string
	 *
	 * @return string The gender string.
	 */
	public function getGenderString()
	{
		return $this->_byMap('gender')->text();
	}

}
