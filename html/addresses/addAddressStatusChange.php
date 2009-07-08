<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['addressStatusChange'])) {
	$addressStatusChange = new AddressStatusChange();
	foreach ($_POST['addressStatusChange'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressStatusChange->$set($value);
	}

	try {
		$addressStatusChange->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$street_address_id = $_REQUEST['street_address_id'];
$template = new Template();
$template->blocks[] = new Block('addresses/addAddressStatusChangeForm.inc', array('street_address_id'=>$street_address_id));
echo $template->render();