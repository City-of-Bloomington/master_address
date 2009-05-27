<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$buildingStatus = new BuildingStatus($_REQUEST['status_code']);
if (isset($_POST['buildingStatus'])) {
	foreach ($_POST['buildingStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$buildingStatus->$set($value);
	}

	try {
		$buildingStatus->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/updateBuildingStatusForm.inc',array('buildingStatus'=>$buildingStatus));
echo $template->render();