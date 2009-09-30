<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

// If they've submitted a valid post, create the new address.
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		$address = Address::createNew($_POST,$changeLogEntry);

		if (!isset($_POST['batch_mode'])) {
			header('Location: '.$address->getStreet()->getURL());
			exit();
		}
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

// If they haven't submitted an address in batch_mode,
// create an address with default information to start from
if (!isset($address)) {
	$address = new Address();

	if (isset($_REQUEST['street_id']) && $_REQUEST['street_id']) {
		try {
			$address->setStreet_id($_REQUEST['street_id']);
			$street = new Street($_REQUEST['street_id']);
		}
		catch (Exception $e) {
			// Ignore any bad streets
		}
	}

	$location = new Location();
	if (isset($_REQUEST['location_id']) && $_REQUEST['location_id']) {
		$location = new Location($_REQUEST['location_id']);
	}
}
else {
	$street = $address->getStreet();

	$location = $address->getLocation();
	$locationData = array();
	$locationData['mailable'] = $location->isMailable($address);
	$locationData['livable'] = $location->isLivable($address);
	$locationData['locationType'] = $location->getLocationType($address);
	unset($location);
}


$template = new Template();
$breadcrumbs = new Block('addresses/breadcrumbs.inc',array('action'=>'add'));
if (isset($street)) {
	$breadcrumbs->street = $street;
}
$template->blocks[] = $breadcrumbs;

// If we've successfully saved the address, let the user know
if ($address->getId()) {
	$template->blocks[] = new Block('addresses/partials/success.inc',
									array('address'=>$address));
}

$addAddressForm = new Block('addresses/addAddressForm.inc',array('address'=>$address));
if (isset($location)) {
	$addAddressForm->location = $location;
}
else {
	$addAddressForm->locationData = $locationData;
}
$template->blocks[] = $addAddressForm;

echo $template->render();