<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param GET address_id
 */
$address = new Address($_GET['address_id']);

if (isset(  $_GET['format'])) {
	switch ($_GET['format']) {
		case 'json':
		case 'xml':
			$template = new Template('default', $_GET['format']);
			break;
		default:
			$template = new Template('two-column');
	}
}
else {

	$template = new Template('two-column');
}

if ($template->outputFormat=='html') {
	$template->blocks[] = new Block('addresses/breadcrumbs.inc', ['address' => $address]);
}
$template->blocks[]     = new Block('addresses/addressInfo.inc', ['address' => $address]);

if ($template->outputFormat=='html') {
	$template->blocks[] = new Block(
		'addresses/addressStatusChangeList.inc',
		['addressStatusChangeList'=>$address->getStatusChangeList()]
	);

	$template->blocks['panel-one'][] = new Block('addresses/locationTabs.inc',['address'    => $address]);
	$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',  ['address'    => $address, 'subunitList'=>$address->getSubunits()]);
	$template->blocks['panel-one'][] = new Block('addresses/purposeList.inc', ['purposeList'=> $address->getPurposes()]);
	if (userIsAllowed('ChangeLog')) {
        $template->blocks['panel-two'][] = new Block('changeLogs/changeLog.inc',  ['target' => $address]);
    }
}
echo $template->render();
