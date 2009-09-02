<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST street_id
 * @param REQUEST action
 */
$errorURL = BASE_URL.'/streets';
if (!userIsAllowed('Street')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header("Location: $errorURL");
	exit();
}
if (!isset($_REQUEST['street_id']) || !$_REQUEST['street_id']
	|| !isset($_REQUEST['action']) || !$_REQUEST['action']) {
	header("Location: $errorURL");
	exit();
}

try {
	$street = new Street($_REQUEST['street_id']);
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

		if (isset($_POST['street'])) {
			$actionFields = array('correct'=>array('town_id','notes'));

			if (count($actionFields[$action])) {
				foreach ($actionFields[$action] as $field) {
					if (isset($_POST['street'][$field])) {
						$set = 'set'.ucfirst($field);
						$street->$set($_POST['street'][$field]);
					}
				}
				$street->save($changeLogEntry);
			}
		}
		else {
			$street->updateChangeLog($changeLogEntry);
		}
		header('Location: '.$street->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
		exit();
	}
}

$template = new Template('two-column');
$template->blocks[] = new Block('streets/breadcrumbs.inc',array('street'=>$street));
$template->blocks[] = new Block("streets/actions/{$action}Form.inc",array('street'=>$street));
$template->blocks['panel-one'][] = new Block('streets/streetNameList.inc',
												array('streetNameList'=>$street->getNames(),
														'street'=>$street));

$template->blocks['panel-one'][] = new Block('addresses/addressList.inc',
												array('addressList'=>$street->getAddresses(),
														'street'=>$street));
echo $template->render();
