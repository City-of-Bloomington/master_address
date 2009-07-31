<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * 
 */

if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}
$street_id = "";
$location = new Location();
$address = new Address();	
if (isset($_GET['steeet_id'])) {
	$street_id = $_GET['street_id'];
}
if(isset($_GET['location_id'])){ // meant the location id
	$location = new Location($_GET['location_id']);
}
if (isset($_POST['address'])) {
	foreach ($_POST['address'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$address->$set($value);
	}

	try {
		$address->save();
		$street_id = $address->getStreet_id();
		if (isset($_POST['location'])) {
			$location = new Location();
			foreach ($_POST['location'] as $field=>$value) {
				$set = 'set'.ucfirst($field);
				$location->$set($value);
			}
			$location->save();
		}
		if(!isset($_POST['batch_mode'])){
			$address = new Address(); // an empty one to populate the form
		}	
		//	header('Location: '.BASE_URL.'/addresses');
		//	exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$street = new Street($street_id);
$addresses = new AddressList(array('street_id'=>$street_id));

$template = new Template();
$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('street'=>$street));
if(count($addresses)){
	$template->blocks[] = new Block('addresses/addressList.inc',array('addressList'=>$addresses));
}
$template->blocks[] = new Block('addresses/addAddressForm.inc',array('street'=>$street,'address'=>$address,'location'=>$location));
echo $template->render();