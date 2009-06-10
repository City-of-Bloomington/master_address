<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$street = new Street($_REQUEST['street_id']);
if (isset($_POST['street'])) {
	foreach ($_POST['street'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$street->$set($value);
	}

	try {
		$street->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetForm.inc',array('street'=>$street));
echo $template->render();