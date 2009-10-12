<?php
/**
 * @copyright 2006-2008 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template('full-width');
$template->blocks[] = new Block('multiSearchForm.inc');

if (isset($_REQUEST['queryType'])) {
	switch ($_REQUEST['queryType']) {
		case 'address':
			$addresses = new AddressList();
			$addresses->search(array('address'=>$_REQUEST['query']));

			// If there's only one address returned, we should display the address
			if (count($addresses) != 1) {
				$template->blocks[] = new Block('addresses/addressList.inc',
												array('addressList'=>$addresses));
			}
			else {
				$address = $addresses[0];
				header('Location: '.BASE_URL.'/addresses/viewAddress.php?address_id='.$address->getId());
				exit();
			}
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
