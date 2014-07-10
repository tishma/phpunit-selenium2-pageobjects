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
 * Class PersonModel
 */
class PersonModel
{
	/**
	 * Person name
	 *
	 * @var
	 */
	protected $name;

	/**
	 * Person gender
	 *
	 * @var
	 */
	protected $gender;

	/**
	 * Male gender
	 */
	const G_MALE = 0;

	/**
	 * Female gender
	 */
	const G_FEMALE = 2;

	/**
	 * Other gender
	 */
	const G_OTHER = 2;

	/**
	 * Sets the real name
	 *
	 * @param string $name The real name.
	 */
	public function setRealName($name)
	{
		$this->name = $name;
	}

	/**
	 * Gets the real name
	 *
	 * @return string The real name.
	 */
	public function getRealName() {
		return $this->name;
	}

	/**
	 * Sets the gender
	 *
	 * @param int $gender The gender number.
	 */
	public function setGender($gender)
	{
		if (in_array($gender, array(self::G_MALE, self::G_FEMALE, self::G_OTHER))) {
			$this->gender = $gender;
		} else {
			$this->gender = self::G_OTHER;
		}
	}

	/**
	 * Gets the gender number
	 *
	 * @return int The gender number.
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * Gets the gender string
	 *
	 * @return string The gender string.
	 */
	public function getGenderString()
	{
		switch ($this->getGender()) {
			case self::G_MALE:
				return 'Male';
			case self::G_FEMALE:
				return 'Female';
			case self::G_OTHER:
				return 'Other';
			default:
				return 'Other';
		}
	}

}
