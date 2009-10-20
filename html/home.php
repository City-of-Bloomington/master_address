<?php
/**
 * @copyright 2006-2008 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$requiredBrowsers = array('Firefox'=>3,'IE'=>8,'Safari'=>4);
$browser = get_browser($_SERVER['HTTP_USER_AGENT'],true);
if (in_array($browser['browser'],array_keys($requiredBrowsers))
	&& (float)$browser < $requiredBrowsers[$browser['browser']]) {
	header('Location: '.BASE_URL.'/requiredBrowsers.php');
	exit();
}


if (isset($_REQUEST['format'])) {
	switch ($_REQUEST['format']) {
		case 'xml':
			$template = new Template('default','xml');
			break;
		default:
			$template = new Template('full-width');
	}
}
else {
	$template = new Template('full-width');
}

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('multiSearchForm.inc');
}

if (isset($_REQUEST['queryType'])) {
	switch ($_REQUEST['queryType']) {
		case 'address':
			$addresses = new AddressList();
			$addresses->search(array('address'=>$_REQUEST['query']));

			// If there's only one address returned, we should display the address
			if (count($addresses) == 1) {
				$address = $addresses[0];
				if ($template->outputFormat == 'html') {
					header('Location: '.BASE_URL.'/addresses/viewAddress.php?address_id='.$address->getId());
					exit();
				}
			}

			$template->blocks[] = new Block('addresses/addressList.inc',
											array('addressList'=>$addresses));
			break;

		case 'street':
			$fields = AddressList::parseAddress($_REQUEST['query'],'streetNameOnly');
			if (count($fields)) {
				$streets = new StreetList($fields);
				$template->blocks[] = new Block('streets/streetList.inc',array('streetList'=>$streets));
			}
			break;
	}
}

echo $template->render();
