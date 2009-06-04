<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$addressSanitation = new AddressSanitation($_REQUEST['street_address_id']);
if (isset($_POST['addressSanitation'])) {
	foreach ($_POST['addressSanitation'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addressSanitation->$set($value);
	}

	try {
		$addressSanitation->save();
		header('Location: '.BASE_URL.'/sanitation');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('sanitation/updateAddressSanitationForm.inc',array('addressSanitation'=>$addressSanitation));
echo $template->render();