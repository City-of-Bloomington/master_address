<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST address
 */
if (isset($_REQUEST['format'])) {
	switch ($_REQUEST['format']) {
		case 'xml':
		case 'txt':
			$template = new Template('default',$_REQUEST['format']);
			break;
		default:
			$template = new Template();
	}
}
else {
	$template = new Template();
}

$search = array();
$searchFields = array('street_number','direction','street_name','streetType',
						'postDirection','city','zip','subunitType','subunitIdentifier');
foreach ($searchFields as $field) {
	if (isset($_REQUEST[$field]) && $_REQUEST[$field]) {
		$search[$field] = $_REQUEST[$field];
	}
}
if (count($search)) {
	$addresses = new AddressList();
	$addresses->find($search);

	// If there's zero addresses, or more than one address, display the addressList
	if (count($addresses) != 1) {
		$addressList = new Block('addresses/addressList.inc',array('addressList'=>$addresses));
	}
	else {
		$address = $addresses[0];
	}
}


// If they ask for an address, load the address they asked for
if (isset($_REQUEST['address_id'])) {
	try {
		$address = new Address($_REQUEST['address_id']);
	}
	catch (Exception $e) {
	}
}



if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('addresses/advancedSearchForm.inc');
	if (isset($addressList)) {
		$template->blocks[] = $addressList;
	}
}
if (isset($address)) {
	if ($template->outputFormat == 'txt') {
		$template->blocks[] = new Block('addresses/verify.inc',array('verified'=>true));
	}
	else {
		$template->blocks[] = new Block('addresses/addressInfo.inc',array('address'=>$address));
	}
}
echo $template->render();
