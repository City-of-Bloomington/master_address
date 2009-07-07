<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['addressHistory'])) {
	$addressHistory = new AddressHistory();
	foreach ($_POST['addressHistory'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressHistory->$set($value);
	}

	try {
		$addressHistory->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$address_street_id = $_REQUEST['address_street_id'];
$template = new Template();
$template->blocks[] = new Block('addresses/addAddressHistoryForm.inc', array('address_street_id'=>$address_street_id));
echo $template->render();