<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET status_code
 */
if (!userIsAllowed('AddressStatus')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

$addressStatus = new AddressStatus($_REQUEST['status_code']);
if (isset($_POST['addressStatus'])) {
	foreach ($_POST['addressStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressStatus->$set($value);
	}

	try {
		$addressStatus->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddressStatusForm.inc',
								array('addressStatus'=>$addressStatus));
echo $template->render();
