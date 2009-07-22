<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Annexation')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/annexations');
	exit();
}

if (isset($_POST['annexation'])) {
	$annexation = new Annexation();
	foreach ($_POST['annexation'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$annexation->$set($value);
	}

	try {
		$annexation->save();
		header('Location: '.BASE_URL.'/annexations');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('annexations/addAnnexationForm.inc');
echo $template->render();