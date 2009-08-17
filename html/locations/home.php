<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (isset($_GET['format'])) {
	switch ($_GET['format']) {
		case 'xml':
			$template = new Template('default','xml');
			break;
		default:
			$template = new Template();
	}
}
else {
	$template = new Template();
}

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('locations/findLocationForm.inc');
}
if (isset($_GET['address'])) {
	$locations = new LocationList();
	$locations->find(array('address'=>$_GET['address']),'street_number');
	$template->blocks[] = new Block('locations/locationList.inc',array('locationList'=>$locations));
}
if ($template->outputFormat == 'html') {
	$purposeList = new PurposeList();
	$purposeList->find();
	$template->blocks[] = new Block('locations/purposeList.inc',array('purposeList'=>$purposeList));

	$types = new LocationTypeList();
	$types->find();
	$template->blocks[] = new Block('locations/locationTypeList.inc',array('locationTypeList'=>$types));
}
echo $template->render();