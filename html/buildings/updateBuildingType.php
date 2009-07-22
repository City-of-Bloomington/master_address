<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET building_type_id
 */
if (!userIsAllowed('BuildingType')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/buildings');
	exit();
}

$buildingType = new BuildingType($_REQUEST['building_type_id']);
if (isset($_POST['buildingType'])) {
	foreach ($_POST['buildingType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$buildingType->$set($value);
	}

	try {
		$buildingType->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/updateBuildingTypeForm.inc',
								array('buildingType'=>$buildingType));
echo $template->render();
