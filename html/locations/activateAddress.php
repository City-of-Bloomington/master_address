<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param POST changeLogEntry
 * @param POST location_id
 * @param POST address_id
 */
if (!userIsAllowed('Address')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

try {
	$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
	$location = new Location($_POST['location_id']);
	$address = new Address($_POST['address_id']);

	$location->activate($address);
	$address->updateChangeLog($changeLogEntry);
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	if (!$address->getId()) {
		header('Location: '.BASE_URL);
		exit();
	}
}

header('Location: '.$address->getURL());