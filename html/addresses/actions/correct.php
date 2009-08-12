<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

$address = new Address($_REQUEST['address_id']);
if (isset($_POST['address'])) {
	$editableFields = array('street_number','address_type','zip','zipplus4',
							'trash_pickup_day','recycle_week','jurisdiction_id',
							'township_id','section','quarter_section','census_block_fips_code',
							'tax_jurisdiction','latitude','longitude',
							'state_plane_x_coordinate','state_plane_y_coordinate');
	foreach ($editableFields as $field) {
		if (isset($_POST[$field])) {
			$set = 'set'.ucfirst($field);
			$address->$set($_POST[$field]);
		}
	}

	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		$address->save($changeLogEntry);
		header('Location: '.$address->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template('two-column');
$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('address'=>$address));
$template->blocks[] = new Block('addresses/actions/correctForm.inc',array('address'=>$address));
$template->blocks[] = new Block('addresses/addressStatusChangeList.inc',
								array('addressStatusChangeList'=>$address->getStatusChangeList()));

$template->blocks['panel-one'][] = new Block('addresses/locationTabs.inc',
												array('address'=>$address));
$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
												array('address'=>$address,
														'subunitList'=>$address->getSubunits()));
$template->blocks['panel-one'][] = new Block('addresses/purposeList.inc',
												array('purposeList'=>$address->getPurposes()));
echo $template->render();
