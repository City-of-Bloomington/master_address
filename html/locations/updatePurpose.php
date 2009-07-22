<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET purpose_id
 */
if (!userIsAllowed('Purpose')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/locations');
	exit();
}

$purpose = new Purpose($_REQUEST['purpose_id']);
if (isset($_POST['purpose'])) {
	foreach ($_POST['purpose'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$purpose->$set($value);
	}

	try {
		$purpose->save();
		header('Location: '.BASE_URL.'/locations');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('locations/updatePurposeForm.inc',array('purpose'=>$purpose));
echo $template->render();