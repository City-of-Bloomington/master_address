<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$streetStatusList = new StreetStatusList();
$streetStatusList->find();

$template = new Template();
$template->blocks[] = new Block('streetStatuses/streetStatusList.inc',
								array('streetStatusList'=>$streetStatusList));
echo $template->render();
