<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$subdivisionName = new SubdivisionName($_REQUEST['subdivision_name_id']);
if (isset($_POST['subdivisionName'])) {
	foreach ($_POST['subdivisionName'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subdivisionName->$set($value);
	}

	try {
		$subdivisionName->save();
		header('Location: '.BASE_URL.'/subdivisions');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('subdivisions/updateSubdivisionNameForm.inc',array('subdivisionName'=>$subdivisionName));
echo $template->render();