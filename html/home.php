<?php
/**
 * Basic Search Page
 *
 * This homepage provides only basic searching for addresses and streets.
 * Only the single input is supported.  If you need the search to be more precise,
 * use the advanced search pages.
 *
 * @copyright 2006-2008 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (isset($_REQUEST['format'])) {
	switch ($_REQUEST['format']) {
		case 'xml':
		case 'json':
			$template = new Template('default',$_REQUEST['format']);
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
			$query = trim($_REQUEST['query']);
			$addresses = new AddressList();
			if (strlen($query)>=3) {
				$addresses->search(array('address'=>$query));
			}

			if ($template->outputFormat == 'html' && count($addresses) == 1) {
				$address = $addresses[0];
				header('Location: '.$address->getURL());
				exit();
			}

			$template->blocks[] = new Block('addresses/addressList.inc',
											array('addressList'=>$addresses));
			break;

		case 'street':
			$query = trim($_REQUEST['query']);
			$fields = AddressList::parseAddress($query,'streetNameOnly');
			if (count($fields)) {
				$streets = new StreetList($fields);
				$template->blocks[] = new Block('streets/streetList.inc',array('streetList'=>$streets));
			}
			break;

		case 'nearest':
			$list = new AddressList();
			$list->find(
				array('latitude'=>$_REQUEST['latitude'],'longitude'=>$_REQUEST['longitude']),
				'distance',1
			);
			#if ($template->outputFormat == 'html' && count($list) == 1) {
			#	$address = $list[0];
			#	header('Location: '.$address->getURL());
			#	exit();
			#}
			$template->blocks[] = new Block('addresses/addressList.inc',array('addressList'=>$list));
			break;
	}
}

if ($template->outputFormat == 'html' && userIsAllowed('ChangeLog')) {
	$paginator = ChangeLog::getPaginator(ChangeLog::getTypes(),ChangeLog::getActions());
	$template->blocks[] = new Block('changeLogs/changeLog.inc',array('paginator'=>$paginator));
}

echo $template->render();
