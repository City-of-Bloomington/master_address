<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['subunitType'])) {
	$subunitType = new SubunitType();
	foreach ($_POST['subunitType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subunitType->$set($value);
	}

	try {
		$subunitType->save();
		header('Location: '.BASE_URL.'/subunits');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('subunits/addSubunitTypeForm.inc');
echo $template->render();