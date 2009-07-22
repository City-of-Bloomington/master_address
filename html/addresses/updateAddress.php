<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

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

$subunits = new SubunitList(array('street_address_id'=>$address->getStreet_address_id()));
$addressStatusChangeList = new AddressStatusChangeList(array('street_address_id'=>$address->getStreet_address_id()));

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddressForm.inc',array('address'=>$address));
$template->blocks[] = new Block('subunits/subunitList.inc',
								array('subunitList'=>$subunits,'address'=>$address));

if(count($addressStatusChangeList)){
	$template->blocks[] = new Block('addresses/addressStatusChangeList.inc',
	array('addressStatusChangeList'=>$addressStatusChangeList));
}

echo $template->render();