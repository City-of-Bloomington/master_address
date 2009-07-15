<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (isset($_GET['format'])) {
	switch ($_GET['format']) {
		case 'xml':
			$template = new Template('default','xml');
			break;
		default:
			$template = new Template();
	}
}
else {
	$template = new Template();
}

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('addresses/findAddressForm.inc');
}
if (isset($_GET['address'])) {
	$addresses = new AddressList();
	$addresses->search(array('address'=>$_GET['address']));

	// If there's only one address returned, we should display the address
	if (count($addresses) != 1) {
		$template->blocks[] = new Block('addresses/addressList.inc',
										array('addressList'=>$addresses));
	}
	else {
		$address = $addresses[0];
	}
}
// If they ask for an address, we should display the address
if (isset($_GET['address_id'])) {
	try {
		$address = new Address($_GET['address_id']);
	}
	catch (Exception $e) {
	}
}
if (isset($address)) {
	$template->blocks[] = new Block('addresses/addressInfo.inc',array('address'=>$address));
}

echo $template->render();
