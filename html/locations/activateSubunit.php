<?php
/**
 * @copyright 2009-2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param POST changeLogEntry
 * @param POST location_id
 * @param POST subunit_id
 */
if (!userIsAllowed('Subunit')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

try {
	$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
	$location = new Location($_POST['location_id']);
	$subunit = new Subunit($_POST['subunit_id']);

	$location->activate($subunit);
	$subunit->updateChangeLog($changeLogEntry);
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	if (!$subunit->getId()) {
		header('Location: '.BASE_URL);
		exit();
	}
}

header('Location: '.$subunit->getURL());