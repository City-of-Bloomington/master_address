<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();

$template->blocks[] = new Block('locations/findLocationForm.inc');
if (isset($_GET['address'])) {
	$locations = new LocationList();
	$locations->find(array('address'=>$_GET['address']),'street_number');
	$template->blocks[] = new Block('locations/locationList.inc',array('locationList'=>$locations));
}

$purposeList = new PurposeList();
$purposeList->find();
$template->blocks[] = new Block('locations/purposeList.inc',array('purposeList'=>$purposeList));

$types = new LocationTypeList();
$types->find();
$template->blocks[] = new Block('locations/locationTypeList.inc',array('locationTypeList'=>$types));

echo $template->render();