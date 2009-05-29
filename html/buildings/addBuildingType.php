<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['buildingType'])) {
	$buildingType = new BuildingType();
	foreach ($_POST['buildingType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$buildingType->$set($value);
	}

	try {
		$buildingType->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/addBuildingTypeForm.inc');
echo $template->render();
