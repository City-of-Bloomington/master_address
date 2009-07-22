<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('SubunitStatus')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subunits');
	exit();
}

if (isset($_POST['subunitStatus'])) {
	$subunitStatus = new SubunitStatus();
	foreach ($_POST['subunitStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subunitStatus->$set($value);
	}

	try {
		$subunitStatus->save();
		header("Location: ".BASE_URL."/addresses/updateAddress.php?street_address_id=".$subunitStatus->getStreet_address_id());
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$subunit = new Subunit($_REQUEST['subunit_id']);

$template = new Template();
$template->blocks[] = new Block('subunits/addSubunitStatusForm.inc',array('subunit'=>$subunit));
echo $template->render();