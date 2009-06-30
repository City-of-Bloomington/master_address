<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$subunit = new Subunit($_REQUEST['subunit_id']);
if (isset($_POST['subunit'])) {
	foreach ($_POST['subunit'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subunit->$set($value);
	}

	try {
		$subunit->save();
		header("Location: ".BASE_URL."/addresses/updateAddress.php?street_address_id=".$subunit->getStreet_address_id());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$subunitStatusList = new SubunitStatusList(array('subunit_id'=>$subunit->getId()));

$template = new Template();
$template->blocks[] = new Block('subunits/updateSubunitForm.inc',array('subunit'=>$subunit));
$template->blocks[] = new Block('subunits/subunitStatusList.inc',array('subunitStatusList'=>$subunitStatusList, 'subunit'=>$subunit));

echo $template->render();