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

class PersonModel
{
	protected $name;
	protected $gender;

	const G_MALE = 0;
	const G_FEMALE = 2;
	const G_OTHER = 2;

	public function setRealName($name)
	{
		$this->name = $name;
	}

	public function getRealName() {
		return $this->name;
	}

	public function setGender($gender)
	{
		if (in_array($gender, array(self::G_MALE, self::G_FEMALE, self::G_OTHER))) {
			$this->gender = $gender;
		} else {
			$this->gender = self::G_OTHER;
		}
	}

	public function getGender() {
		return $this->gender;
	}

	public function getGenderString()
	{
		switch ($this->getGender()) {
			case self::G_MALE:
				return 'Male';
				break;
			case self::G_FEMALE:
				return 'Female';
				break;
			case self::G_OTHER:
				return 'Other';
				break;
		}
	}
}
