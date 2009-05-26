<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET state_road_id
 */

verifyUser('Administrator');

$stateRoad = new StateRoad($_REQUEST['stateRoad_id']);
if (isset($_POST['stateRoad'])) {
	foreach ($_POST['stateRoad'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$stateRoad->$set($value);
	}

	try {
		$stateRoad->save();
		header('Location: '.BASE_URL.'/stateRoads');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('stateRoads/updateStateRoadForm.inc',array('stateRoad'=>$stateRoad));
echo $template->render();
