<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('SubdivisionName')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subdivisions');
	exit();
}

if (isset($_POST['subdivisionName'])) {
	$subdivisionName = new SubdivisionName();
	foreach ($_POST['subdivisionName'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$subdivisionName->$set($value);
	}

	try {
		$subdivisionName->save();
		header('Location: '.BASE_URL.'/subdivisions');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$subdivision = new Subdivision($_REQUEST['subdivision_id']);
$template = new Template();
$template->blocks[] = new Block('subdivisions/addSubdivisionNameForm.inc', array('subdivision'=>$subdivision));
echo $template->render();