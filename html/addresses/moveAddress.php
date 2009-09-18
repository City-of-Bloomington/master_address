<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@boomington.in.gov>
 * @param REQUEST address_id
 * @param REQUEST old_location_id
 * @param REQUEST new_location_id
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}
try {
	$address = new Address($_REQUEST['address_id']);
	$old_location = new Location($_REQUEST['old_location_id']);
	if (isset($_REQUEST['new_location_id']) && $_REQUEST['new_location_id']) {
		$target_location = new Location($_REQUEST['new_location_id']);
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	if (isset($address)) {
		header('Location: '.$address->getURL());
	}
	else {
		header('Location: '.BASE_URL.'/addresses');
	}
	exit();
}

// Once we know the location, do all the database work
if (isset($target_location)) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		$old_location->moveAddress($address,$target_location);
		$address->updateChangeLog($changeLogEntry);
		header('Location: '.$address->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}



$moveAddressForm = new Block('addresses/moveAddressForm.inc',
							array('location'=>$old_location,'address'=>$address));
// If they aren't sure, they may have done a search
if (isset($_REQUEST['location_search'])) {
	$list = new LocationList(array('address'=>$_REQUEST['location_search']));
	$moveAddressForm->locationList = $list;
}


$template = new Template();
$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('address'=>$address));
$template->blocks[] = $moveAddressForm;
echo $template->render();
