<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * @param GET subunit_id
 * @param GET action
 */
$errorURL = BASE_URL.'/addresses';
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header("Location: $errorURL");
	exit();
}
if (!isset($_REQUEST['subunit_id']) || !$_REQUEST['subunit_id']
	|| !isset($_REQUEST['action']) || !$_REQUEST['action']) {
	header("Location: $errorURL");
	exit();
}

try {
	$subunit = new Subunit($_REQUEST['subunit_id']);
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header("Location: $errorURL");
	exit();
}

$action = $_REQUEST['action'];

// All actions will involve updating the change log
// Some actions do not involve changing any fields of a subunit
// However, we still want to update the change log when these actions occur.
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		switch ($action) {
			case 'correct':
				$subunit->setSudType($_POST['sudtype']);
				$subunit->setNotes($_POST['notes']);
				$subunit->setIdentifier($_POST['street_subunit_identifier']);
				$subunit->save($changeLogEntry);

				$location = new Location($_POST['location_id']);
				$data = array();
				$data['mailable'] = isset($_POST['mailable']);
				$data['livable'] = isset($_POST['livable']);
				$data['locationType'] = $_POST['location_type_id'];
				$location->update($data,$subunit);
				break;

			case 'retire':
			case 'unretire':
			case 'verify':				
				$subunit->$action($changeLogEntry);
				break;
		}

		header('Location: '.$subunit->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template('two-column');
$template->blocks[] = new Block('subunits/breadcrumbs.inc',array('subunit'=>$subunit));
$template->blocks[] = new Block("subunits/actions/{$action}Form.inc",array('subunit'=>$subunit));
$template->blocks[] = new Block('subunits/subunitStatusChangeList.inc',
								array('subunitStatusChangeList'=>$subunit->getStatusChangeList()));
$template->blocks[] = new Block('changeLogs/changeLog.inc',
								array('changeLog'=>$subunit->getChangeLog()));


$address = $subunit->getAddress();
$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
											array('address'=>$address,
											'subunitList'=>$address->getSubunits()));

echo $template->render();
