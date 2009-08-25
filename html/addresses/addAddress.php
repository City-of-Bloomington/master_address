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
$street = new Street();
$location = new Location();
$address = new Address();
if (isset($_REQUEST['street_id'])) {
	$street = new Street($_REQUEST['street_id']);
}
if (isset($_REQUEST['lid']) && $_REQUEST['lid']){ // meant the location id
	$location = new Location($_REQUEST['lid']);
}
if (isset($_POST['address'])) {
	foreach ($_POST['address'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$address->$set($value);
	}
	$address->setStreet_id($street->getId());
	try {
		$changeLog = new ChangeLogEntry($_SESSION['USER'],array('action'=>'assign'));
		$address->save($changeLog);
		//
		$status = new AddressStatusChange();
		$status->setStreet_address_id($address->getId());
		$status->setStatus_code(1);
		$status->save();
		
		if(!isset($_POST['lid']) || !$_POST['lid']){
			$location = new Location();
			$location->setStreet_address_id($address->getStreet_address_id());
		}
		
		if($_POST['location']){
			foreach ($_POST['location'] as $field=>$value) {
				$set = 'set'.ucfirst($field);
				$location->$set($value);
			}
			$location->save();			
		}
		
		if(!isset($_POST['batch_mode'])){
			header('Location: '.$address->getStreet()->getURL());			
			exit();			
		}	

	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();

$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('street'=>$street));

$template->blocks[] = new Block('addresses/addAddressForm.inc',array('street'=>$street,'address'=>$address,'location'=>$location));
echo $template->render();