<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['stateRoad'])) {
	$state_road_master = new StateRoadMaster();
	foreach ($_POST['stateRoad'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$state_road->$set($value);
	}

	try {
		$state_road->save();
		header('Location: '.BASE_URL.'/stateRoads');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('stateRoads/addStateRoadForm.inc');
echo $template->render();
