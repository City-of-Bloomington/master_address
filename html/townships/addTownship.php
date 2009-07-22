<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Township')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/townships');
	exit();
}

if (isset($_POST['township'])) {
	$township = new Township();
	foreach ($_POST['township'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$township->$set($value);
	}

	try {
		$township->save();
		header('Location: '.BASE_URL.'/townships');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('townships/addTownshipForm.inc');
echo $template->render();
