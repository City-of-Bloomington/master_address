<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST action
 * @param REQUEST street_id			You must provide either street or streetName
 * @param REQUEST streetName_id		You must provide either street or streetName
 */
$errorURL = BASE_URL.'/streets';
if (!userIsAllowed('Street')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header("Location: $errorURL");
	exit();
}
if (!isset($_REQUEST['action']) || !$_REQUEST['action']) {
	header("Location: $errorURL");
	exit();
}

$action = $_REQUEST['action'];

try {
	if (isset($_REQUEST['streetName_id'])) {
		$streetName = new StreetName($_REQUEST['streetName_id']);
		$street = $streetName->getStreet();
	}
	else {
		$street = new Street($_REQUEST['street_id']);
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header("Location: $errorURL");
	exit();
}


// All actions will involve updating the change log
// Some actions do not involve changing any fields of an address.
// However, we still want to update the change log when these actions occur.
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);

		switch ($action) {
			case 'correct':
				$streetName->correct($_POST,$changeLogEntry);
				break;

			case 'alias':
				$street->addStreetName($_POST,$changeLogEntry);
				break;

			case 'change':
				$street->changeStreetName($_POST,$changeLogEntry);
				break;
		}
		header('Location: '.$street->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template('two-column');
$template->blocks[] = new Block('streets/breadcrumbs.inc',array('street'=>$street));
$template->blocks[] = new Block('streets/streetInfo.inc',array('street'=>$street));
$template->blocks[] = new Block('changeLogs/changeLog.inc',
								array('changeLog'=>$street->getChangeLog()));


$parameters = array('street'=>$street);
if (isset($streetName)) {
	$parameters['streetName'] = $streetName;
}
$template->blocks['panel-one'][] = new Block("streets/actions/{$action}Form.inc",$parameters);

$template->blocks['panel-one'][] = new Block('streets/streetNameList.inc',
												array('streetNameList'=>$street->getNames(),
														'street'=>$street));

$template->blocks['panel-one'][] = new Block('addresses/addressList.inc',
												array('addressList'=>$street->getAddresses(),
														'street'=>$street));
echo $template->render();
