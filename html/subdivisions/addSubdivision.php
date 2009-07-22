<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Subdivision')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subdivisions');
	exit();
}

if (isset($_POST['subdivision'])) {
	$subdivision = new Subdivision();
	foreach ($_POST['subdivision'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subdivision->$set($value);
	}

	try {
		$subdivision->save();
		header('Location: '.BASE_URL.'/subdivisions');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('subdivisions/addSubdivisionForm.inc');
echo $template->render();