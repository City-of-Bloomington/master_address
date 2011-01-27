<?php
/**
 * A URL for parsing address strings into the parts of an address
 *
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param $_GET address
 */

$template = isset($_REQUEST['format'])
	? new Template('default',$_REQUEST['format'])
	: new Template();

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('addresses/parseAddressForm.inc');
}

if (isset($_GET['address'])) {
	$address = AddressList::parseAddress($_GET['address']);
	$template->blocks[] = new Block('addresses/parseAddressResults.inc',array('address'=>$address));
}

echo $template->render();
