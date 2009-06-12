<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['streetName'])) {
	$streetName = new StreetName();
	foreach ($_POST['streetName'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$streetName->$set($value);
	}

	try {
		$streetName->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$street = new Street($_REQUEST['street_id']);
$streetNameList = $street->getStreetNameList();

$template = new Template();
$template->blocks[] = new Block('streets/addStreetNameForm.inc', array('street'=>$street));
$template->blocks[] = new Block('streets/streetNameList.inc', array('streetNameList'=>$streetNameList,'street'=>$street));

echo $template->render();
