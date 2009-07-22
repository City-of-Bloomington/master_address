<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('LocationChange')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/locations');
	exit();
}

if (isset($_POST['locationChange'])) {
	$locationChange = new LocationChange();
	foreach ($_POST['locationChange'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$locationChange->$set($value);
	}

	try {
		$locationChange->save();
		header('Location: '.BASE_URL.'/locations');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('locations/addLocationChangeForm.inc');
echo $template->render();