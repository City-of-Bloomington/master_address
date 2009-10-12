<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST address
 */
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

	// A valid address should return one and only one result
	if (count($addresses) == 1) {
		$address = $addresses[0];
	}
}





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
if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('addresses/verifyAddressForm.inc');
}
if (isset($address)) {
	$template->blocks[] = new Block('addresses/addressInfo.inc',array('address'=>$address));
}
else {
	if (count($search)) {
		$template->blocks[] = new Block('addresses/invalid.inc');
	}
}
echo $template->render();
