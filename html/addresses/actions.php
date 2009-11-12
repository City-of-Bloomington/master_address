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
if (!isset($_REQUEST['address_id']) || !$_REQUEST['address_id']) {
	$_SESSION['errorMessages'][] = new Exception('addresses/unknownAddress');
	header("Location: $errorURL");
	exit();
}
if (!isset($_REQUEST['action']) || !$_REQUEST['action']) {
	$_SESSION['errorMessages'][] = new Exception('addresses/unknownAction');
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
// Each of these actions has been written into the Address class.
// The Address class will handle saving whatever needs to be saved to the database.
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		if (in_array($action,Address::getActions())) {
			$address->$action($_POST,$changeLogEntry);

			header('Location: '.$address->getURL());
			exit();
		}
		else {
			$_SESSION['errorMessages'][] = new Exception('addresses/unknownAction');
		}
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
$template->blocks[] = new Block('changeLogs/changeLog.inc',array('target'=>$address));

$template->blocks['panel-one'][] = new Block('addresses/locationTabs.inc',
												array('address'=>$address,'deactivateButtons'=>true));
$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
												array('address'=>$address,
														'subunitList'=>$address->getSubunits(),
														'deactivateButtons'=>true));
$template->blocks['panel-one'][] = new Block('addresses/purposeList.inc',
												array('purposeList'=>$address->getPurposes()));
echo $template->render();
