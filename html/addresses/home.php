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
	$template->blocks[] = new Block('addresses/addressList.inc',
									array('addressList'=>$addresses));
}

echo $template->render();
