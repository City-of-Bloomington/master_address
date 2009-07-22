<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Town')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/towns');
	exit();
}

if (isset($_POST['town'])) {
	$town = new Town();
	foreach ($_POST['town'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$town->$set($value);
	}

	try {
		$town->save();
		header('Location: '.BASE_URL.'/towns');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('towns/addTownForm.inc');
echo $template->render();
