<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET status_code
 */

verifyUser('Administrator');

$streetStatus = new StreetStatus($_REQUEST['status_code']);
if (isset($_POST['streetStatus'])) {
	foreach ($_POST['streetStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$streetStatus->$set($value);
	}

	try {
		$streetStatus->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetStatusForm.inc',
								array('streetStatus'=>$streetStatus));
echo $template->render();
