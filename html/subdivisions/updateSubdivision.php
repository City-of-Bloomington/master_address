<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$subdivision = new Subdivision($_REQUEST['subdivision_id']);
if (isset($_POST['subdivision'])) {
	foreach ($_POST['subdivision'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subdivision->$set($value);
	}

	try {
		$subdivision->save();
		header('Location: '.BASE_URL.'/subdivision');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$subdivisionNameList = $subdivision->getSubdivisionNameList();

$template = new Template();
$template->blocks[] = new Block('subdivisions/updateSubdivisionForm.inc',array('subdivision'=>$subdivision));
$template->blocks[] = new Block('subdivisions/subdivisionNameList.inc',array('subdivisionNameList'=>$subdivisionNameList,'subdivision'=>$subdivision));

echo $template->render();