<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET annexation_id
 */
if (!userIsAllowed('Annexation')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/annexations');
	exit();
}

$annexation = new Annexation($_REQUEST['annexation_id']);
if (isset($_POST['annexation'])) {
	foreach ($_POST['annexation'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$annexation->$set($value);
	}

	try {
		$annexation->save();
		header('Location: '.BASE_URL.'/annexations');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('annexations/updateAnnexationForm.inc',
								array('annexation'=>$annexation));
echo $template->render();