<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('AddressStatusChange')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/addresses');
	exit();
}

$addressStatusChange = new AddressStatusChange($_REQUEST['id']);
if (isset($_POST['addressStatusChange'])) {
	foreach ($_POST['addressStatusChange'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressStatusChange->$set($value);
	}

	try {
		$addressStatusChange->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddressStatusChangeForm.inc',array('addressStatusChange'=>$addressStatusChange));
echo $template->render();