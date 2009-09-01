<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@boomington.in.gov>
 * @param REQUEST address_id
 * @param REQUEST old_lid
 * @param REQUEST new_lid
 * @param REQUEST new_location_id
 */

$address = new Address($_REQUEST['address_id']);
$old_location = new Location($_REQUEST['old_lid']);

// Check and use a new LID if they gave one
if (isset($_REQUEST['new_lid']) && $_REQUEST['new_lid']) {
	try {
		$target_location = new Location($_REQUEST['new_lid']);
		$target_location_id = $target_location->getLocation_id();
	}
	catch (Exception $e) {
		// Just ignore it if the new lid is invalid
	}
}

// If they gave us a location_id, make sure it's a real one
if (!isset($target_location)
	&& isset($_REQUEST['new_location_id']) && $_REQUEST['new_location_id']) {
	$list = new LocationList(array('location_id'=>$_REQUEST['location_id']));
	if (count($list)) {
		$target_location_id = (int)$_REQUEST['new_location_id'];
	}
}


// Once we know the location, do all the database work
if (isset($target_location_id)) {
	// Create a new LID for this address, using data copied from the old location
	// Update the status on the old location with the status the user chose
	$newLocation = clone($old_location);
	$newLocation->setLocation_id($target_location_id);
	$newLocation->toggleActive();

	try {
		// Make sure they're not trying to move the address to the same location
		if ($old_location->getLocation_id() != $newLocation->getLocation_id()) {
			$old_location->saveStatus($_REQUEST['old_location_status']);

			$newLocation->saveStatus('CURRENT');
			$changeLog = new ChangeLogEntry($_SESSION['USER'],array('action'=>'move'));
			$newLocation->save($changeLog);
		}
		header('Location: '.$address->getURL());
		exit();
	}
	catch(Exception $e){
		print_r($e);
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
