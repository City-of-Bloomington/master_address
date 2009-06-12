<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
verifyUser('Administrator');

if (isset($_POST['locationType'])) {
	$locationType = new LocationType();
	foreach ($_POST['locationType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$locationType->$set($value);
	}

	try {
		$locationType->save();
		header('Location: '.BASE_URL.'/locations');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('locations/addLocationTypeForm.inc');
echo $template->render();