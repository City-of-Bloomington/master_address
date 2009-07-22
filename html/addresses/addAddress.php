<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

if (isset($_POST['address'])) {
	$address = new Address();
	foreach ($_POST['address'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$mast_address->$set($value);
	}

	try {
		$address->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/addAddressForm.inc');
echo $template->render();