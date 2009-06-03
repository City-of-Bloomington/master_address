<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$addrLocationPurpose = new AddrLocationPurpose($_REQUEST['location_purpose_id']);
if (isset($_POST['addrLocationPurpose'])) {
	foreach ($_POST['addrLocationPurpose'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addrLocationPurpose->$set($value);
	}

	try {
		$addrLocationPurpose->save();
		header('Location: '.BASE_URL.'/addresses');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addresses/updateAddrLocationPurposeForm.inc',array('addrLocationPurpose'=>$addrLocationPurpose));
echo $template->render();