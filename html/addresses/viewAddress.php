<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 */
$address = new Address($_GET['address_id']);

if (isset($_GET['format'])) {
	switch ($_GET['format']) {
		case 'xml':
			$template = new Template('default','xml');
			break;
		default:
			$template = new Template('two-column');
	}
}
else {
	$template = new Template('two-column');
}

$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('address'=>$address));
$template->blocks[] = new Block('addresses/addressInfo.inc',array('address'=>$address));
$template->blocks[] = new Block('addresses/addressStatusChangeList.inc',
								array('addressStatusChangeList'=>$address->getStatusChangeList()));

$template->blocks['panel-one'][] = new Block('addresses/locationTabs.inc',
												array('address'=>$address));
$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
												array('address'=>$address,
														'subunitList'=>$address->getSubunits()));
$template->blocks['panel-one'][] = new Block('addresses/purposeList.inc',
												array('purposeList'=>$address->getPurposes()));
echo $template->render();