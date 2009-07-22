<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('AddressStatus')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

if (isset($_POST['addressStatus'])) {
	$addressStatus = new AddressStatus();
	foreach ($_POST['addressStatus'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressStatus->$set($value);
	}

	try {
		$addressStatus->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/addAddressStatusForm.inc');
echo $template->render();
