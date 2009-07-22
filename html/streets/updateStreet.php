<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Street')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/streets');
	exit();
}

$street = new Street($_REQUEST['street_id']);
if (isset($_POST['street'])) {
	foreach ($_POST['street'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$street->$set($value);
	}

	try {
		$street->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}
$streetNameList = $street->getStreetNameList();

$template = new Template();
$template->blocks[] = new Block('streets/updateStreetForm.inc',array('street'=>$street));
$template->blocks[] = new Block('streets/streetNameList.inc',array('streetNameList'=>$streetNameList,'street'=>$street));
echo $template->render();