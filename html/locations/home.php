<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();

$purposeList = new PurposeList();
$purposeList->find();
$template->blocks[] = new Block('locations/purposeList.inc',array('purposeList'=>$purposeList));

$types = new LocationTypeList();
$types->find();
$template->blocks[] = new Block('locations/locationTypeList.inc',array('locationTypeList'=>$types));

echo $template->render();