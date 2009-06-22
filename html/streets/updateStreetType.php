<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param streetType $this->streetType
 */

verifyUser('Administrator');

$streetType = new StreetType($_REQUEST['streetType_id']);
if (isset($_POST['streetType'])) {
	foreach ($_POST['streetType'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$streetType->$set($value);
	}

	try {
		$streetType->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetTypeForm.inc',
								array('streetType'=>$streetType));
echo $template->render();
