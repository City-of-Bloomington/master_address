<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$addressHistory = new AddressHistory($_REQUEST['id']);
if (isset($_POST['addressHistory'])) {
	foreach ($_POST['addressHistory'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressHistory->$set($value);
	}

	try {
		$addressHistory->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddressHistoryForm.inc',array('addressHistory'=>$addressHistory));
echo $template->render();