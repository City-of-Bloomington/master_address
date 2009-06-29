<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['subunit'])) {
	$subunit = new Subunit();
	foreach ($_POST['subunit'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subunit->$set($value);
	}

	try {
		$subunit->save();
		header("Location: ".BASE_URL."/addresses/updateAddress.php?street_address_id=".$subunit->getStreet_address_id());
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$address = new Address($_GET['street_address_id']);
$template = new Template();
$template->blocks[] = new Block('subunits/addSubunitForm.inc', array('address'=>$address));
echo $template->render();