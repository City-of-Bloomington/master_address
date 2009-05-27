<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET buildingType_id
 */

verifyUser('Administrator');

$buildingType = new BuildingType($_REQUEST['buildingType_id']);
if (isset($_POST['buildingType'])) {
	foreach ($_POST['buildingType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$buildingType->$set($value);
	}

	try {
		$buildingType->save();
		header('Location: '.BASE_URL.'/buildingTypes');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildingTypes/updateBuildingTypeForm.inc',
								array('buildingType'=>$buildingType));
echo $template->render();
