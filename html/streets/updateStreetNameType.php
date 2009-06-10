<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$streetNameType = new StreetNameType($_REQUEST['street_name_type']);
if (isset($_POST['streetNameType'])) {
	foreach ($_POST['streetNameType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$streetNameType->$set($value);
	}

	try {
		$streetNameType->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetNameTypeForm.inc',array('streetNameType'=>$streetNameType));
echo $template->render();