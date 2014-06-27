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
?>
<h1 id="title">Example!</h1>
<form action="view.php" method="post">
    <p>
        <label for="your_name">Your Name:</label>
        <input id="your_name" type="text" name="your_name" value="" />
    </p>
    
    <p>
        <label for="gender">Your Gender:</label>
        <select id="gender" name="gender">
            <option value="0">Male</option>
            <option value="1">Female</option>
            <option value="2">Other</option>
        </select>
    </p>

    <p>
        <input id="form_submit" type="submit" name="submit" value="Save" />
    </p>
</form>
