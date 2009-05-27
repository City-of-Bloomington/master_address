<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['buildings_status_lookup'])) {
	$buildingStatus = new BuildingStatus();
	foreach ($_POST['buildingStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$buildingStatus->$set($value);
	}

	try {
		$buildingStatus->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/addBuildingStatusForm.inc');
echo $template->render();