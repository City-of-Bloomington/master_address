<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('StreetName')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/streets');
	exit();
}

$streetName = new StreetName($_REQUEST['id']);

if (isset($_POST['streetName'])) {
	foreach ($_POST['streetName'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$streetName->$set($value);
	}

	try {
		$streetName->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$street = $streetName->getStreet();
$streetNameList = $street->getStreetNameList();

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetNameForm.inc',array('streetName'=>$streetName));
$template->blocks[] = new Block('streets/streetNameList.inc',array('streetNameList'=>$streetNameList,'street'=>$street));
echo $template->render();