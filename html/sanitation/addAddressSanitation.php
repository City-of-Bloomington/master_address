<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

    $address_street_id = $_REQUEST['address_street_id'];
if (isset($_POST['addressSanitation'])) {
	$addressSanitation = new AddressSanitation();
	foreach ($_POST['addressSanitation'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressSanitation->$set($value);
	}

	try {
		$addressSanitation->save();
		header('Location: '.BASE_URL.'/sanitation');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
if(isset($_REQUEST['address_street_id']))
  $template->blocks[] = new Block('sanitations/addAddressSanitationForm.inc',
								  array('address_street_id'=>$_REQUEST['address_street_id']));  
else
  $template->blocks[] = new Block('addresses/findAddressForm.inc');

echo $template->render();