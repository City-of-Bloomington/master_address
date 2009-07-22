<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Building')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/buildings');
	exit();
}

if (isset($_POST['building'])) {
	$building = new Building();
	foreach ($_POST['building'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$building->$set($value);
	}

	try {
		$building->save();
		header('Location: '.BASE_URL.'/buildings');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('buildings/addBuildingForm.inc');
echo $template->render();