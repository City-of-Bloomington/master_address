<?php
/**
 * Advanced Address Search
 *
 * You must provide each of fields seperately.  This form will not respond to
 * a single address field.  This search will do loose matching
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST address
 */
if (isset($_REQUEST['format'])) {
	switch ($_REQUEST['format']) {
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
	$addresses->search($search);

	if ($template->outputFormat == 'html' && count($addresses)==1) {
		$address = $addresses[0];
		header('Location: '.$address->getURL());
		exit();
	}
}


if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('addresses/advancedSearchForm.inc');
}
if (isset($addresses)) {
	$template->blocks[] = new Block('addresses/addressList.inc',array('addressList'=>$addresses));
}

echo $template->render();
