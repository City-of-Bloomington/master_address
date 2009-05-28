<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$building = new Building($_REQUEST['building_id']);
if (isset($_POST['building'])) {
	foreach ($_POST['building'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$building->$set($value);
	}

	try {
		$building->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/updateBuildingForm.inc',array('building'=>$building));
echo $template->render();