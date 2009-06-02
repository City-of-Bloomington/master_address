<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$addressLocationChange = new AddressLocationChange($_REQUEST['location_change_id']);
if (isset($_POST['addressLocationChange'])) {
	foreach ($_POST['addressLocationChange'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressLocationChange->$set($value);
	}

	try {
		$addressLocationChange->save();
		header('Location: '.BASE_URL.'/addressChange');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addressChange/updateAddressLocationChangeForm.inc',array('addressLocationChange'=>$addressLocationChange));
echo $template->render();