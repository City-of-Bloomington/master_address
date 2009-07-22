<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET locationType_id
 */
if (!userIsAllowed('LocationType')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/locations');
	exit();
}

$locationType = new LocationType($_REQUEST['locationType_id']);
if (isset($_POST['locationType'])) {
	foreach ($_POST['locationType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$locationType->$set($value);
	}

	try {
		$locationType->save();
		header('Location: '.BASE_URL.'/locations');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('locations/updateLocationTypeForm.inc',
								array('locationType'=>$locationType));
echo $template->render();