<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET jurisdiction_id
 */
if (!userIsAllowed('Jurisdiction')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/jurisdictions');
	exit();
}

$jurisdiction = new Jurisdiction($_REQUEST['jurisdiction_id']);
if (isset($_POST['jurisdiction'])) {
	foreach ($_POST['jurisdiction'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$jurisdiction->$set($value);
	}

	try {
		$jurisdiction->save();
		header('Location: '.BASE_URL.'/jurisdictions');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('jurisdictions/updateJurisdictionForm.inc',
								array('jurisdiction'=>$jurisdiction));
echo $template->render();
