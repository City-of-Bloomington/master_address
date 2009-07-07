<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 */

verifyUser('Administrator');

$address = new Address($_REQUEST['street_address_id']);
if (isset($_POST['address'])) {
	foreach ($_POST['address'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$address->$set($value);
	}

	try {
		$address->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
	
}
$subunits = new SubunitList();
$subunits->find(array('street_address_id'=>$address->getStreet_address_id()));
$addressHistory = new AddressHistory();
$addressHistory->find(array('street_address_id'=>$address->getStreet_address_id());

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddressForm.inc',array('address'=>$address));
$template->blocks[] = new Block('addresses/addressHistoryList.inc',array('addressHistoryList'=>$addressHistory));					  
$template->blocks[] = new Block('subunits/subunitList.inc',array('subunitList'=>$subunits, 'address'=>$address));
echo $template->render();