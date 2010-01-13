<?php
/**
 * @copyright 2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = isset($_GET['format']) ? new Template('default',$_GET['format']) : new Template();
$template->blocks[] = new Block('addresses/zipcodeList.inc',
								array('zipcodes'=>Address::getZipCodes()));
echo $template->render();