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
if (isset($_REQUEST['lid']) && $_REQUEST['lid']) { // meant the location id
	$location = new Location($_REQUEST['lid']);
}

$action = isset($_POST['action']) ? $_POST['action'] : 'add';

if (isset($_POST['action'])) {
	$fields = array('street_id','street_number',
					'address_type','tax_jurisdiction','jurisdiction_id','township_id',
					'section','quarter_section','subdivision_id','plat_id','plat_lot_number',
					'street_address_2','city','state','zip','zipplus4',
					'latitude','longitude','state_plane_x_coordinate','state_plane_y_coordinate',
					'census_block_fips_code','notes');
	foreach ($fields as $field) {
		if (isset($_POST[$field])) {
			$set = 'set'.ucfirst($field);
			$address->$set($_POST[$field]);
		}
	}

	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],array('action'=>$action));
		$address->save($changeLogEntry);
		$address->saveStatus('CURRENT');

		if (!$_POST['lid']) {
			$location = new Location();
			$location->setAddress($address);
		}

		$location->setMailable(isset($_POST['mailable']));
		$location->setLivable(isset($_POST['livable']));
		$location->setLocation_type_id($_POST['location_type_id']);
		$location->save($changeLogEntry);

		if (!isset($_POST['batch_mode'])) {
			header('Location: '.$address->getStreet()->getURL());
			exit();
		}
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/breadcrumbs.inc',
								array('street'=>$street,'action'=>$action));

// If we've successfully saved the address, let the user know
if ($address->getId()) {
	$template->blocks[] = new Block('addresses/partials/success.inc',
									array('address'=>$address));
}
$template->blocks[] = new Block('addresses/addAddressForm.inc',
								array('address'=>$address,'location'=>$location));
echo $template->render();