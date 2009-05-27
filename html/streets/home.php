<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();


$streetStatusList = new StreetStatusList();
$streetStatusList->find();
$template->blocks[] = new Block('streets/streetStatusList.inc',
								array('streetStatusList'=>$streetStatusList));

$directionList = new DirectionList();
$directionList->find();
$template->blocks[] = new Block('streets/directionList.inc',array('directionList'=>$directionList));


$suffixList = new SuffixList();
$suffixList->find();
$template->blocks[] = new Block('streets/suffixList.inc',array('suffixList'=>$suffixList));


echo $template->render();
