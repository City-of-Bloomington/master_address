<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$buildingTypeList = new BuildingTypeList();
$buildingTypeList->find();

$template = new Template();
$template->blocks[] = new Block('buildingTypes/buildingTypeList.inc',
								array('buildingTypeList'=>$buildingTypeList));
echo $template->render();
