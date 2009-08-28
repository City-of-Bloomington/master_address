<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@boomington.in.gov>
 * @param GET address
 */

$template = new Template();
$location = new Location($_REQUEST['lid']);
$address = new Address($_REQUEST['street_address_id']);
if (isset($_GET['new_address']) && $_GET['new_address']) {
	$addresses = new AddressList();
	$addresses->search(array('address'=>$_GET['new_address']));
	//
	$list = array();
	foreach($addresses as $address){
		$locList = $address->getLocations();
		foreach($locList as $loc){
			$list[] = $loc;
		}
	}
}

$new_lid="";
if(isset($_REQUEST['new_lid']) && $_REQUEST['new_lid']){
	$new_lid=$_REQUEST['new_lid'];
}
else if(isset($_REQUEST['new_lid2']) && $_REQUEST['new_lid2']){
	$new_lid=$_REQUEST['new_lid2'];
}

if($new_lid){
	//
	// do the swap and change status
	//
	$newLocation = new Location($new_lid);
	$loc = $newLocation->clone();
	$loc->setAddress($address);	
	$loc->toggleActive();
	try{
		$loc->save();
		$status = $location->getStatus(); // locationStatusChange
		$status->setEffective_end_date(new Date());
		$status->save();
		//
		$status = new LocationStatusChange();
		$status->setStatus_code($_REQUEST['new_status']);
		$status->setLocation($location);
		$status->save();
		//
		$status = newLocation->getStatus();
		$status->setEffective_end_date(new Date());
		$status->save();
		//
		$status = new LocationStatusChange();
		$status->setStatus_code($newLocation->getStatus()->getStatus_code());
		$status->setLocation($newLocation);
		$status->save();
	}
	catch(Exception $e){
		$_SESSION['errorMessages'][] = $e;
	}
	
}
$template = new Template();
if(isset($list)){
	$template->blocks[] = new Block('addresses/moveAddressForm.inc',
										array('locationList'=>$list,'location'=>$location,'address'=>$address));
}
else{
	$template->blocks[] = new Block('addresses/moveAddressForm.inc',
										array('location'=>$location,'address'=>$address));
}

echo $template->render();
