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

require_once __DIR__ . '/../PersonModel.php';

$name = isset($_POST['your_name']) ? $_POST['your_name'] : '';
$name = htmlspecialchars($name);
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';

$p = new PersonModel();
$p->setRealName($name);
$p->setGender($gender);

?>
<h1 id="title">Viewing your data</h1>
<table>
    <tr>
        <th>Your Name</th>
        <td id="output_your_name"><?php echo $p->getRealName(); ?>
    </tr>
    <tr>
        <th>Your Gender</th>
        <td id="output_your_gender"><?php echo $p->getGenderString(); ?>
    </tr>
</table>
