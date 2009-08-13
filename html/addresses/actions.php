<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET address_id
 * @param GET action
 */
$errorURL = BASE_URL.'/addresses';
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header("Location: $errorURL");
	exit();
}
if (!isset($_REQUEST['address_id']) || !$_REQUEST['address_id']
	|| !isset($_REQUEST['action']) || !$_REQUEST['action']) {
	header("Location: $errorURL");
	exit();
}

try {
	$address = new Address($_REQUEST['address_id']);
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header("Location: $errorURL");
	exit();
}

$action = $_REQUEST['action'];


// All actions will involve updating the change log
// Some actions do not involve changing any fields of an address.
// However, we still want to update the change log when these actions occur.
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);

		if (isset($_POST['address'])) {
			$actionFields = array('correct'=>array('street_number','address_type','zip','zipplus4',
													'trash_pickup_day','recycle_week','jurisdiction_id',
													'township_id','section','quarter_section',
													'census_block_fips_code','tax_jurisdiction',
													'latitude','longitude',
													'state_plane_x_coordinate','state_plane_y_coordinate'),
									'change'=>array('street_number')
									);
			foreach ($actionFields[$action] as $field) {
				if (isset($_POST[$field])) {
					$set = 'set'.ucfirst($field);
					$address->$set($_POST[$field]);
				}
			}

			$address->save($changeLogEntry);
		}
		else {
			$address->updateChangeLog($changeLogEntry);
		}
		header('Location: '.$address->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}




$template = new Template('two-column');
$template->blocks[] = new Block('addresses/breadcrumbs.inc',array('address'=>$address));
$template->blocks[] = new Block("addresses/actions/{$action}Form.inc",array('address'=>$address));
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
