<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET id
 */
if (!userIsAllowed('SubunitStatus')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subunits');
	exit();
}

$subunitStatus = new SubunitStatus($_REQUEST['id']);
if (isset($_POST['subunitStatus'])) {
	foreach ($_POST['subunitStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subunitStatus->$set($value);
	}

	try {
		$subunitStatus->save();
		header("Location: ".BASE_URL."/addresses/updateAddress.php?street_address_id=".$subunitStatus->getStreet_address_id());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('subunits/updateSubunitStatusForm.inc',array('subunitStatus'=>$subunitStatus));
echo $template->render();